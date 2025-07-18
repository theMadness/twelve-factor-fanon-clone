#!/bin/bash

# --- Global Variables ---
# These will be set by functions and used throughout the script.
SCRIPT_DIR=""
PROJECT_ROOT_DIR=""
VENDOR_DIR=""
MISSING_NAME_FILES=()
HIDDEN_REQUIRED_BUT_AVAILABLE=()
REQUIRED_AND_UNAVAILABLE=()
UNMANAGED_DEPS_FOR_JSON=() # NEW: Array to collect truly unmanaged deps for JSON export

# --- Functions ---

# Function to check for necessary external commands
_check_prerequisites() {
    if ! command -v jq &> /dev/null; then
        echo "Error: 'jq' is not installed. Please install it (e.g., sudo apt install jq) to run this script."
        exit 1
    fi
    if ! command -v composer &> /dev/null; then
        echo "Error: 'composer' is not installed. Please install it to run this script."
        exit 1
    fi
}

# Function to set up necessary paths (script location, project root, vendor dir)
_set_paths() {
    # Get the directory where this script is located
    SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
    # Assume project root is one level up from the script's directory (e.g., if script is in 'scripts/')
    PROJECT_ROOT_DIR="$(dirname "$SCRIPT_DIR")"
    VENDOR_DIR="$PROJECT_ROOT_DIR/vendor"

    echo "Project Root: $PROJECT_ROOT_DIR"
    echo "Vendor Directory: $VENDOR_DIR"
    echo "------------------------------------------------------------------------------------------------------------------"

    # Check if the vendor directory exists
    if [ ! -d "$VENDOR_DIR" ]; then
        echo "Error: The '$VENDOR_DIR' directory does not exist. Please run 'composer install' first,"
        echo "or ensure this script is run from a location where '$VENDOR_DIR' is correct (e.g., from your project root or its 'scripts' subdirectory)."
        exit 1
    fi
}

# Function to find composer.json files missing a 'name' field
_find_unmanaged_composer_files() {
    echo "Searching for composer.json files in $VENDOR_DIR that Composer *might* not have fully processed (missing 'name' field):"
    echo "------------------------------------------------------------------------------------------------------------------"

    local found_files_raw
    # Read null-separated filenames into an array
    # < <(...) uses process substitution to avoid subshell issues with array modification
    mapfile -d $'\0' -t found_files_raw < <(find "$VENDOR_DIR" -name composer.json -print0)

    for file in "${found_files_raw[@]}"; do
        # Remove the trailing null character that mapfile might include for the last element
        file="${file%$'\0'}"

        # Skip empty strings (can happen if find output is empty or malformed)
        if [[ -z "$file" ]]; then
            continue
        fi

        local name_field
        name_field=$(jq -r '.name // empty' "$file")

        if [ -z "$name_field" ]; then
            echo "Potential problematic composer.json found (missing 'name' field):"
            echo "  Path: $file"
            echo "  Parent Directory: $(dirname "$file")"
            echo "---"
            MISSING_NAME_FILES+=("$file") # Add to global array
        fi
    done
}

# Function to analyze the status of each required dependency
_analyze_dependency_status() {
    echo "Analyzing dependencies from identified problematic composer.json files..."
    echo "------------------------------------------------------------------------------------------------------------------"

    for file in "${MISSING_NAME_FILES[@]}"; do
        local require_deps_list_raw
        require_deps_list_raw=$(jq -r '.require | to_entries[] | .key + " " + .value' "$file" 2>/dev/null)

        if [ -n "$require_deps_list_raw" ]; then
            # Loop through each dependency in the 'require' section of the current composer.json
            while IFS= read -r line; do
                local package_name version_constraint status_message
                package_name=$(echo "$line" | awk '{print $1}')
                version_constraint=$(echo "$line" | awk '{print $2}')

                if [ -z "$package_name" ] || [ -z "$version_constraint" ]; then
                    # Skip malformed lines from jq output
                    continue
                fi

                # --- Determine if it's a PHP Extension (ext-*) or a regular Composer Package ---
                if [[ "$package_name" =~ ^ext-.*$ || "$package_name" =~ ^lib-.*$ ]]; then
                    # It's a PHP extension or system library requirement
                    # Use composer depends to check if it's required by any managed package in the project via `composer depends`
                    if (cd "$PROJECT_ROOT_DIR" && composer depends "$package_name" &> /dev/null); then
                        status_message="System requirement, tracked by a managed package/project"
                        HIDDEN_REQUIRED_BUT_AVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                    else
                        status_message="System requirement, NOT tracked by a managed package/project"
                        REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                    fi
                else
                    # It's a regular Composer package
                    local is_installed=false
                    # Check if the package is physically installed in vendor/ using `composer show --installed`
                    if (cd "$PROJECT_ROOT_DIR" && composer show --installed "$package_name" &> /dev/null); then
                        is_installed=true
                    fi

                    local is_tracked_in_lockfile=false
                    # Check if the package is at least present in the composer.lock file
                    if (cd "$PROJECT_ROOT_DIR" && composer show --locked "$package_name" &> /dev/null); then
                        is_tracked_in_lockfile=true
                    fi

                    local check_output check_exit_code
                    if $is_installed; then
                        # Scenario A: Package is physically installed in vendor/
                        check_output=$( (cd "$PROJECT_ROOT_DIR" && composer why-not "$package_name" "$version_constraint" 2>&1) )
                        check_exit_code=$?

                        if [ "$check_exit_code" -eq 0 ] && [ -z "$check_output" ]; then
                            # Installed and satisfies the constraint.
                            status_message="SATISFIED (Physically installed and meets constraint)"
                            HIDDEN_REQUIRED_BUT_AVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        elif [ "$check_exit_code" -eq 0 ] && [ -n "$check_output" ]; then
                            # Installed, but composer why-not gives output (e.g., "installed at X, does not satisfy Y")
                            status_message="INSTALLED, BUT DOES NOT SATISFY (Installed version conflicts with constraint): $check_output"
                            REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        else # check_exit_code is 1 (or other non-zero) for why-not, but we know it's installed
                            # This implies a more complex conflict even though it's installed.
                            status_message="INSTALLED, BUT HAS CONFLICTS/ISSUES (Installed, but underlying conflicts exist): $check_output"
                            REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        fi
                    elif $is_tracked_in_lockfile; then
                        # Scenario B: Package is NOT physically installed, but IS tracked in composer.lock
                        # This could be a dev dependency not installed, or something else (e.g., optional, skipped).
                        # We still use why-not to see if the *locked* version would satisfy the *new* constraint
                        check_output=$( (cd "$PROJECT_ROOT_DIR" && composer why-not "$package_name" "$version_constraint" 2>&1) )
                        check_exit_code=$?

                        if [ "$check_exit_code" -eq 0 ] && [ -z "$check_output" ]; then
                            status_message="TRACKED IN LOCKFILE (Constraint met, but not physically installed)"
                            HIDDEN_REQUIRED_BUT_AVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        elif [ "$check_exit_code" -eq 0 ] && [ -n "$check_output" ]; then
                             status_message="TRACKED IN LOCKFILE (Constraint not met by locked version, or details provided): $check_output"
                             REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        else # check_exit_code is 1
                            status_message="TRACKED IN LOCKFILE (Conflicts in lockfile prevent satisfaction): $check_output"
                            REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        fi
                    else
                        # Scenario C: Package is NEITHER physically installed NOR tracked in composer.lock
                        # This is a truly unmanaged and absent dependency from the main project's perspective.
                        status_message="NOT TRACKED IN LOCKFILE (Truly unmanaged and absent)"
                        REQUIRED_AND_UNAVAILABLE+=("  - $package_name:$version_constraint ($status_message) [Source: $file]")
                        # Add this specific type of dependency to the list for JSON export
                        UNMANAGED_DEPS_FOR_JSON+=("$package_name:$version_constraint")
                    fi
                fi # End if for PHP Extension vs. Composer Package
            done <<< "$require_deps_list_raw"
        fi # End if require_deps_list_raw is not empty
    done # End for file in MISSING_NAME_FILES
}

# NEW FUNCTION: Exports the truly unmanaged dependencies to a JSON file
_export_unmanaged_deps_to_json() {
    local output_file="$PROJECT_ROOT_DIR/hidden-dependencies.json"

    if [ ${#UNMANAGED_DEPS_FOR_JSON[@]} -eq 0 ]; then
        echo "No 'Truly unmanaged and absent' dependencies found to export to JSON. Skipping file creation."
        # If the file exists from a previous run, remove it to ensure clean state
        [ -f "$output_file" ] && rm "$output_file"
        return 0
    fi

    echo "Exporting 'Truly unmanaged and absent' dependencies to JSON file: $output_file"
    # Build JSON object using jq:
    # 1. printf each "name:version" string on a new line
    # 2. jq -R reads each raw line
    # 3. split(":", 2) splits into [name, version] (max 2 parts in case version has colons)
    # 4. { (.[0]): .[1] } creates {"name": "version"} objects
    # 5. jq -s 'add' slurps all objects into an array and then merges them into a single object
    printf "%s\n" "${UNMANAGED_DEPS_FOR_JSON[@]}" | \
    jq -R 'split(":", 2) | { (.[0]): .[1] }' | \
    jq -s 'add' > "$output_file"

    if [ $? -eq 0 ]; then
        echo "Successfully created $output_file"
    else
        echo "Error: Failed to create $output_file"
    fi
    echo "------------------------------------------------------------------------------------------------------------------"
}


# Function to print the categorized results
_print_summary() {
    echo ""
    echo "###################################################################################"
    echo "### Summary of Hidden Requirements from Unmanaged composer.json files           ###"
    echo "###################################################################################"

    # --- List 1: Hidden but already available/satisfied ---
    if [ ${#HIDDEN_REQUIRED_BUT_AVAILABLE[@]} -gt 0 ]; then
        echo ""
        echo "### Hidden Requirements Already Satisfied/Available ###"
        echo "These dependencies are required by a 'hidden' composer.json, and are either:"
        echo "  - Physically installed and meet the constraint."
        echo "  - Tracked in your composer.lock and meet the constraint (even if not physically installed)."
        echo "  - Or, are PHP/system extensions already covered by your project's platform requirements."
        echo "-----------------------------------------------------------------------------------"
        for dep in "${HIDDEN_REQUIRED_BUT_AVAILABLE[@]}"; do
            echo "$dep"
        done
        echo "-----------------------------------------------------------------------------------"
    else
        echo "No hidden requirements found that are already satisfied/available."
        echo "-----------------------------------------------------------------------------------"
    fi

    # --- List 2: Hidden and currently unavailable/unmanaged ---
    if [ ${#REQUIRED_AND_UNAVAILABLE[@]} -gt 0 ]; then
        echo ""
        echo "### Hidden Requirements Currently Unavailable/Unmanaged ###"
        echo "These dependencies are required by a 'hidden' composer.json, and are NOT currently"
        echo "satisfied by your project's managed dependencies or system PHP environment. "
        echo "They might be missing, conflicting, or not tracked by your main Composer setup."
        echo "Consider explicitly adding these to your main composer.json if needed."
        echo "-----------------------------------------------------------------------------------"
        for dep in "${REQUIRED_AND_UNAVAILABLE[@]}"; do
            echo "$dep"
        done
        echo "-----------------------------------------------------------------------------------"
    else
        echo "No hidden requirements found that are currently unavailable/unmanaged."
        echo "-----------------------------------------------------------------------------------"
    fi
}


# --- Main Script Execution ---
main() {
    _check_prerequisites
    _set_paths

    _find_unmanaged_composer_files

    if [ ${#MISSING_NAME_FILES[@]} -eq 0 ]; then
        echo "No composer.json files found that are missing a 'name' field in '$VENDOR_DIR'."
        echo "This means all detected composer.json files appear to be properly named packages (or are not intended as such)."
    else
        _analyze_dependency_status
        _export_unmanaged_deps_to_json # Call the new function here
        _print_summary
    fi

    echo "------------------------------------------------------------------------------------------------------------------"
    echo "Scan complete."
}

# Call the main function
main
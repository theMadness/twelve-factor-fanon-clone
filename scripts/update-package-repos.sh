#!/usr/bin/env bash
set -euo pipefail

COMPOSER_JSON="${COMPOSER_JSON:-composer.json}"
VENDOR_DIR="${VENDOR_DIR:-vendor}"

if ! command -v jq >/dev/null 2>&1; then
  echo "jq is required but not installed." >&2
  exit 1
fi

if [ ! -f "$COMPOSER_JSON" ]; then
  echo "composer.json not found at $COMPOSER_JSON" >&2
  exit 1
fi

# Collect package repository entries with name + source.url + source.reference
# Only from repositories[] entries of type "package"
mapfile -t PKG_REPOS < <(jq -r '
  .repositories
  | map(select(.type == "package") | .package
        | select(.name and .source and .source.url and .source.reference)
        | [.name, .source.url, .source.reference]
  )
  | .[]
  | @tsv
' "$COMPOSER_JSON")

short_sha() { echo "${1:0:7}"; }
is_sha() { [[ "$1" =~ ^[0-9a-fA-F]{7,40}$ ]]; }

for line in "${PKG_REPOS[@]}"; do
  IFS=$'\t' read -r NAME _URL REF <<<"$line"
  PKG_DIR="$VENDOR_DIR/$NAME"
  GIT_DIR="$PKG_DIR/.git"

  # Only operate on packages that are actually git repos in vendor/
  [ -d "$GIT_DIR" ] || continue

  pushd "$PKG_DIR" >/dev/null

  # Ensure we have an origin; if missing, set it to the repo URL
  if ! git remote get-url origin >/dev/null 2>&1; then
      echo "$NAME: no origin found" >&2
      continue
  fi

  LOCAL_SHA="$(git rev-parse HEAD)"

  if is_sha "$REF"; then
    continue
  else
    # Reference is a branch/tag name. Resolve remote head freshly.
    REMOTE_HEAD="$(git ls-remote --heads origin "$REF" | awk '{print $1}' | head -n1 || true)"
    if [ -z "$REMOTE_HEAD" ]; then
      # Try tags if not a head
      REMOTE_HEAD="$(git ls-remote --tags origin "refs/tags/$REF" | awk '{print $1}' | head -n1 || true)"
    fi
    if [ -z "$REMOTE_HEAD" ]; then
      # Attempt a shallow fetch of that ref and re-resolve
      git fetch --quiet --depth=1 origin "$REF" || true
      REMOTE_HEAD="$(git ls-remote --heads origin "$REF" | awk '{print $1}' | head -n1 || true)"
      if [ -z "$REMOTE_HEAD" ]; then
        REMOTE_HEAD="$(git ls-remote --tags origin "refs/tags/$REF" | awk '{print $1}' | head -n1 || true)"
      fi
    fi
    if [ -z "$REMOTE_HEAD" ]; then
      echo "$NAME: ref '$REF' not found on origin" >&2
      popd >/dev/null
      continue
    fi
    # Fetch latest for the ref (shallow) and set target to the fetched commit
    git fetch --quiet --depth=1 origin "$REF" || true
    TARGET_SHA="$(git rev-parse --verify FETCH_HEAD 2>/dev/null || echo "$REMOTE_HEAD")"
  fi

  if [ "$LOCAL_SHA" != "$TARGET_SHA" ]; then
    git reset --hard "$TARGET_SHA" >/dev/null
    # Minimal, useful output only on change
    if is_sha "$REF"; then
      echo "$NAME updated: $(short_sha "$LOCAL_SHA") -> $(short_sha "$TARGET_SHA") (commit)"
    else
      echo "$NAME updated: $(short_sha "$LOCAL_SHA") -> $(short_sha "$TARGET_SHA") (ref: $REF)"
    fi
  fi

  popd >/dev/null
done
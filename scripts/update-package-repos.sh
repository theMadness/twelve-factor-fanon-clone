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

# Read direct dependencies: "<name>\t<constraint>"
mapfile -t DIRECT_DEPS < <(jq -r '.require | to_entries[] | "\(.key)\t\(.value)"' "$COMPOSER_JSON")

short_sha() { echo "${1:0:7}"; }

for entry in "${DIRECT_DEPS[@]}"; do
  PACKAGE="${entry%%$'\t'*}"
  CONSTRAINT="${entry#*$'\t'}"

  PKG_DIR="$VENDOR_DIR/$PACKAGE"
  GIT_DIR="$PKG_DIR/.git"
  [ -d "$GIT_DIR" ] || continue

  # Only process dev-branch constraints: dev-BRANCH[ @stability ]
  if [[ "$CONSTRAINT" =~ ^dev-([A-Za-z0-9._/-]+)(@.*)?$ ]]; then
    BRANCH="${BASH_REMATCH[1]}"
  else
    continue
  fi

  pushd "$PKG_DIR" >/dev/null

  # Query remote head for the branch
  REMOTE_HEAD="$(git ls-remote --heads origin "$BRANCH" | awk '{print $1}' | head -n1 || true)"
  if [ -z "$REMOTE_HEAD" ]; then
    # Try fetching the branch shallowly to refresh refs
    git fetch --quiet --depth=1 origin "$BRANCH" || true
    REMOTE_HEAD="$(git ls-remote --heads origin "$BRANCH" | awk '{print $1}' | head -n1 || true)"
  fi
  if [ -z "$REMOTE_HEAD" ]; then
    echo "$PACKAGE: branch $BRANCH not found on origin" >&2
    popd >/dev/null
    continue
  fi

  # Fetch latest branch tip (shallow)
  git fetch --quiet --depth=1 origin "$BRANCH" || true

  LOCAL_SHA="$(git rev-parse HEAD)"
  # Prefer FETCH_HEAD when available; fall back to remote ref SHA
  TARGET_SHA="$(git rev-parse --verify FETCH_HEAD 2>/dev/null || echo "$REMOTE_HEAD")"

  if [ "$LOCAL_SHA" != "$TARGET_SHA" ]; then
    git reset --hard "$TARGET_SHA" >/dev/null
    echo "$PACKAGE updated: $(short_sha "$LOCAL_SHA") -> $(short_sha "$TARGET_SHA") (dev-$BRANCH)"
  fi

  popd >/dev/null
done
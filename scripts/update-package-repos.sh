#!/bin/bash
set -e

COMPOSER_JSON="composer.json"
command -v jq >/dev/null 2>&1 || { echo >&2 "jq is required but not installed."; exit 1; }

DIRECT_DEPS=$(jq -r '.require | keys[]' "$COMPOSER_JSON")

for PACKAGE in $DIRECT_DEPS; do
  REPO_PATH="vendor/$PACKAGE"
  [ -d "$REPO_PATH/.git" ] || continue

  pushd "$REPO_PATH" > /dev/null

  # Fetch silently
  git fetch --depth=1 origin HEAD > /dev/null 2>&1

  LOCAL=$(git rev-parse HEAD)
  REMOTE=$(git rev-parse FETCH_HEAD)

  if [ "$LOCAL" != "$REMOTE" ]; then
    if git merge --ff-only FETCH_HEAD > /dev/null 2>&1; then
      echo -n "$PACKAGE fast-forwarding: $LOCAL → $REMOTE"
    else
      git reset --hard FETCH_HEAD > /dev/null
      echo -n "$PACKAGE reset: $LOCAL → $REMOTE"
    fi
  fi

  popd > /dev/null
done

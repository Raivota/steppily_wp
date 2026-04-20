#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: $0 path/to/uploads-directory|uploads.tar|uploads.tar.gz"
  exit 1
fi

SOURCE_PATH="$1"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env ]]; then
  echo ".env file missing"
  exit 1
fi

if [[ ! -e "$SOURCE_PATH" ]]; then
  echo "Source not found: $SOURCE_PATH"
  exit 1
fi

TARGET_DIR="/var/www/html/wp-content/uploads"

if [[ -d "$SOURCE_PATH" ]]; then
  tar -C "$SOURCE_PATH" -czf - . | docker compose exec -T wordpress sh -c \
    "mkdir -p '$TARGET_DIR' && tar -xzf - -C '$TARGET_DIR'"
  echo "Restored uploads directory into Docker volume"
  exit 0
fi

case "$SOURCE_PATH" in
  *.tar.gz|*.tgz)
    cat "$SOURCE_PATH" | docker compose exec -T wordpress sh -c \
      "mkdir -p '$TARGET_DIR' && tar -xzf - -C '$TARGET_DIR'"
    ;;
  *.tar)
    cat "$SOURCE_PATH" | docker compose exec -T wordpress sh -c \
      "mkdir -p '$TARGET_DIR' && tar -xf - -C '$TARGET_DIR'"
    ;;
  *)
    echo "Unsupported source: use a directory, .tar, .tar.gz, or .tgz"
    exit 1
    ;;
esac

echo "Restored uploads archive into Docker volume"

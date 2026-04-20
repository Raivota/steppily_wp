#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: $0 path/to/dump.sql[.gz]"
  exit 1
fi

DUMP_FILE="$1"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env ]]; then
  echo ".env file missing"
  exit 1
fi

if [[ ! -f "$DUMP_FILE" ]]; then
  echo "Dump file not found: $DUMP_FILE"
  exit 1
fi

set -a
source .env
set +a

if [[ "$DUMP_FILE" == *.gz ]]; then
  gunzip -c "$DUMP_FILE" | docker compose exec -T db mariadb -u root -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}"
else
  cat "$DUMP_FILE" | docker compose exec -T db mariadb -u root -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}"
fi

echo "Restore complete"

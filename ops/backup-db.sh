#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env ]]; then
  echo ".env file missing"
  exit 1
fi

set -a
source .env
set +a

mkdir -p backups
TIMESTAMP="$(date +%F-%H%M%S)"
OUT_FILE="backups/${DOMAIN:-karjaaristuudio.ee}-db-${TIMESTAMP}.sql"

docker compose exec -T db mariadb-dump \
  -u root \
  -p"${MYSQL_ROOT_PASSWORD}" \
  --single-transaction \
  --quick \
  --lock-tables=false \
  "${MYSQL_DATABASE}" > "$OUT_FILE"

gzip "$OUT_FILE"
echo "Created ${OUT_FILE}.gz"

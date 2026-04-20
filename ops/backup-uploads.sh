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
OUT_FILE="backups/${DOMAIN:-karjaaristuudio.ee}-uploads-${TIMESTAMP}.tar.gz"

docker compose exec -T wordpress sh -c \
  'test -d /var/www/html/wp-content/uploads && tar -czf - -C /var/www/html/wp-content/uploads .' \
  > "$OUT_FILE"

echo "Created ${OUT_FILE}"

#!/usr/bin/env bash
set -euo pipefail

ORIGINAL_ENTRYPOINT="/usr/local/bin/docker-entrypoint.sh"
EXTRA_FILE="/usr/src/wordpress/wp-config-docker-extra.php"
TARGET_CONFIG="/var/www/html/wp-config.php"
INCLUDE_MARKER="wp-config-docker-extra.php"

if [ "$#" -eq 0 ]; then
  set -- apache2-foreground
fi

if [ "${1:-}" = 'apache2-foreground' ] || [ "${1#-}" != "$1" ]; then
  "$ORIGINAL_ENTRYPOINT" apache2-foreground &
  child_pid=$!

  for _ in $(seq 1 30); do
    if [ -f "$TARGET_CONFIG" ]; then
      break
    fi
    sleep 1
  done

  if [ -f "$TARGET_CONFIG" ] && [ -f "$EXTRA_FILE" ] && ! grep -q "$INCLUDE_MARKER" "$TARGET_CONFIG"; then
    python3 - <<'PY'
from pathlib import Path
cfg = Path('/var/www/html/wp-config.php')
text = cfg.read_text()
needle = "if ( !defined('ABSPATH') )"
insert = "if (file_exists(__DIR__ . '/wp-config-docker-extra.php')) {\n    require_once __DIR__ . '/wp-config-docker-extra.php';\n}\n\n"
if needle in text and insert not in text:
    text = text.replace(needle, insert + needle, 1)
    cfg.write_text(text)
PY
    cp "$EXTRA_FILE" /var/www/html/wp-config-docker-extra.php
  elif [ -f "$EXTRA_FILE" ] && [ ! -f /var/www/html/wp-config-docker-extra.php ]; then
    cp "$EXTRA_FILE" /var/www/html/wp-config-docker-extra.php
  fi

  wait "$child_pid"
  exit $?
fi

exec "$ORIGINAL_ENTRYPOINT" "$@"

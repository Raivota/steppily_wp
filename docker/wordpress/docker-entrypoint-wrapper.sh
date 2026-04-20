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
    tmp_file="$(mktemp)"
    inserted=0
    while IFS= read -r line; do
      clean_line="${line%$'\r'}"
      if [ "$inserted" -eq 0 ] && [ "$clean_line" = "if ( !defined('ABSPATH') )" ]; then
        cat <<'EOF' >> "$tmp_file"
if (file_exists(__DIR__ . '/wp-config-docker-extra.php')) {
    require_once __DIR__ . '/wp-config-docker-extra.php';
}

EOF
        inserted=1
      fi
      printf '%s\n' "$clean_line" >> "$tmp_file"
    done < "$TARGET_CONFIG"

    if [ "$inserted" -eq 1 ]; then
      mv "$tmp_file" "$TARGET_CONFIG"
    else
      rm -f "$tmp_file"
    fi
    cp "$EXTRA_FILE" /var/www/html/wp-config-docker-extra.php
  elif [ -f "$EXTRA_FILE" ] && [ ! -f /var/www/html/wp-config-docker-extra.php ]; then
    cp "$EXTRA_FILE" /var/www/html/wp-config-docker-extra.php
  fi

  wait "$child_pid"
  exit $?
fi

exec "$ORIGINAL_ENTRYPOINT" "$@"

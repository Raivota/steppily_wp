<?php
/**
 * Extra runtime config for Docker deployments.
 */

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

if (!defined('WP_HOME') && getenv('WP_HOME')) {
    define('WP_HOME', getenv('WP_HOME'));
}

if (!defined('WP_SITEURL') && getenv('WP_SITEURL')) {
    define('WP_SITEURL', getenv('WP_SITEURL'));
}

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', filter_var(getenv('WP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN));
}

if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', filter_var(getenv('WP_DEBUG_LOG') ?: false, FILTER_VALIDATE_BOOLEAN));
}

if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', filter_var(getenv('WP_DEBUG_DISPLAY') ?: false, FILTER_VALIDATE_BOOLEAN));
}

if (!defined('AUTOMATIC_UPDATER_DISABLED')) {
    define('AUTOMATIC_UPDATER_DISABLED', true);
}

if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

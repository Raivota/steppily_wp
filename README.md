# karjaaristuudio.ee Docker starter

This repo is set up for migrating an existing WordPress site into Docker without putting WordPress core itself into Git.

The intended split is:
- WordPress core comes from the Docker image
- versioned site code lives in `wp-content`
- root-level custom files live in `wordpress-root`
- uploads live in a Docker volume
- database lives in a Docker volume
- production content is restored from exported backups

That is the right direction for `https://karjaaristuudio.ee/`. You do **not** want to commit the whole live hosting file tree from the screenshot into Git.

## What belongs where

### Commit to Git

Copy these from the existing server into this repo:
- `wp-content/themes`
- `wp-content/plugins`
- `wp-content/mu-plugins`
- selected root-level custom files such as `.htaccess`, `robots.txt`, `favicon.ico`, verification HTML files, and other non-core files into `wordpress-root/`

Only copy root files that are truly yours. Do not copy WordPress core files such as:
- `wp-admin`
- `wp-includes`
- `wp-activate.php`
- `wp-blog-header.php`
- `wp-comments-post.php`
- `wp-config-sample.php`
- `wp-cron.php`
- `wp-links-opml.php`
- `wp-load.php`
- `wp-login.php`
- `wp-mail.php`
- `wp-settings.php`
- `wp-signup.php`
- `wp-trackback.php`
- `xmlrpc.php`

### Keep out of Git

These are runtime data, backups, or cache and should stay out of Git:
- database dumps
- `wp-content/uploads`
- `wp-content/cache`
- `wp-content/upgrade`
- `wp-content/upgrade-temp-backup`
- `wp-content/wflogs`
- `wp-content/updraft`
- plugin-generated backup folders

### Persist in Docker volumes

- MariaDB data in `db_data`
- media uploads in `uploads_data`

## Repo layout

- `docker-compose.yml`: WordPress, MariaDB, and Nginx
- `docker/wordpress/`: custom WordPress image and runtime config
- `docker/nginx/default.conf.template`: domain-aware Nginx config
- `wordpress-root/`: versioned root-level custom files copied into WordPress
- `wp-content/`: versioned themes, plugins, and mu-plugins
- `ops/`: helper scripts for DB and uploads backup/restore

## First migration plan

### 1. Prepare the repo

```bash
cp .env.example .env
```

Then update at least:
- `WORDPRESS_DB_PASSWORD`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `HTTP_PORT`
- `WP_HOME`
- `WP_SITEURL`

`HTTP_PORT` defaults to `8080` so this stack does not immediately fight for port `80` with your backend or a future reverse proxy on the VPS.

### 2. Copy the versioned site code

From the current hosting account, copy:
- real theme code into `wp-content/themes`
- required plugin code into `wp-content/plugins`
- any must-use plugins into `wp-content/mu-plugins`
- root-level custom files into `wordpress-root`

If a plugin is commercial and not managed by Composer, keeping the plugin code in Git is usually the pragmatic option for a VPS deployment.

### 3. Export production data

From the current host, export:
- a full MySQL dump
- the full `wp-content/uploads` directory

Keep those exports outside Git.

### 4. Boot the stack

```bash
docker compose up -d --build
```

### 5. Restore the database

```bash
./ops/restore-db.sh /path/to/production.sql
```

or

```bash
./ops/restore-db.sh /path/to/production.sql.gz
```

### 6. Restore uploads into the Docker volume

If you exported uploads as a directory:

```bash
./ops/restore-uploads.sh /path/to/uploads
```

If you exported uploads as a tarball:

```bash
./ops/restore-uploads.sh /path/to/uploads.tar.gz
```
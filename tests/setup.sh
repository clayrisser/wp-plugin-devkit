#!/bin/sh

set -e

. "$(dirname -- "$0")/helper.sh"

dc up -d --wait database wordpress
wp core is-installed 2>/dev/null || wp core install \
	--url="$WP_URL" \
	--title="WP Plugin DevKit" \
	--admin_user=admin \
	--admin_password=wordpress \
	--admin_email=admin@example.com \
	--skip-email

# Shared helpers for the bats e2e suite. POSIX sh.
PROJECT_ROOT="${PROJECT_ROOT:-$(cd "${BATS_TEST_DIRNAME:-${BATS_SUITE_DIRNAME:-$(dirname -- "$0")}}/.." && pwd)}"
COMPOSE_FILE="$PROJECT_ROOT/docker/compose.yaml"
COMPOSE_PROJECT_NAME="${COMPOSE_PROJECT_NAME:-wp-plugin-devkit-test}"
# The test stack binds its own port so it can coexist with the dev stack.
WORDPRESS_PORT="${TEST_WORDPRESS_PORT:-18888}"
WP_URL="http://localhost:$WORDPRESS_PORT"
export COMPOSE_PROJECT_NAME WORDPRESS_PORT WP_URL

dc() {
	docker compose -f "$COMPOSE_FILE" --project-name "$COMPOSE_PROJECT_NAME" "$@"
}

wp() {
	dc run --rm -T wpcli wp "$@"
}

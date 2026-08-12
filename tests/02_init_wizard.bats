#!/usr/bin/env bats
load helper.sh

setup_file() {
	WIZARD_DIR="$BATS_FILE_TMPDIR/devkit"
	mkdir -p "$WIZARD_DIR"
	(cd "$PROJECT_ROOT" && tar -cf - --exclude .git --exclude .make --exclude vendor --exclude .env --exclude .env.bak .) |
		tar -xf - -C "$WIZARD_DIR"
	export WIZARD_DIR
}

@test "init wizard renames the plugin (defaults on EOF)" {
	run sh -c 'cd "$WIZARD_DIR" && printf "My Cool Plugin\n" | php tools.php init'
	[ "$status" -eq 0 ]
	[ -d "$WIZARD_DIR/my-cool-plugin" ]
	[ ! -d "$WIZARD_DIR/plugin-devkit" ]
	[ -f "$WIZARD_DIR/my-cool-plugin/my-cool-plugin.php" ]
	[ -f "$WIZARD_DIR/my-cool-plugin/includes/class-my-cool-plugin.php" ]
}

@test "init wizard rewrites the plugin bootstrap headers" {
	grep -q 'Plugin Name:       My Cool Plugin' "$WIZARD_DIR/my-cool-plugin/my-cool-plugin.php"
	grep -q 'class My_Cool_Plugin {' "$WIZARD_DIR/my-cool-plugin/includes/class-my-cool-plugin.php"
	grep -q 'Text Domain:       my-cool-plugin' "$WIZARD_DIR/my-cool-plugin/my-cool-plugin.php"
}

@test "init wizard rewrites the dev environment files" {
	grep -q 'my-cool-plugin:/var/www/html/wp-content/plugins/my-cool-plugin' "$WIZARD_DIR/docker/compose.yaml"
	grep -q 'my-cool-plugin/vendor' "$WIZARD_DIR/composer.json"
	grep -q 'my-cool-plugin' "$WIZARD_DIR/phpcs.xml.dist"
}

@test "init wizard leaves no stale placeholders behind" {
	run grep -rEl 'plugin-devkit|plugin_devkit|Plugin_Devkit|Plugin Devkit' "$WIZARD_DIR" \
		--exclude-dir=vendor --exclude-dir=assets --exclude=composer.lock
	[ "$status" -ne 0 ]
}

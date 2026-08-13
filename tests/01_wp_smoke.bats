#!/usr/bin/env bats
load helper.sh

@test "wordpress core is installed" {
	run wp core is-installed
	[ "$status" -eq 0 ]
}

@test "wordpress serves the homepage" {
	run curl -fsS -o /dev/null -w '%{http_code}' "$WP_URL"
	[ "$status" -eq 0 ]
	[ "$output" = "200" ]
}

@test "plugin-devkit is visible to wordpress" {
	run wp plugin list --field=name
	[ "$status" -eq 0 ]
	echo "$output" | grep -qx plugin-devkit
}

@test "plugin-devkit activates without errors" {
	run wp plugin activate plugin-devkit
	[ "$status" -eq 0 ]
	echo "$output" | grep -q 'Success:'
}

@test "plugin-devkit is active" {
	run wp plugin is-active plugin-devkit
	[ "$status" -eq 0 ]
}

@test "plugin classes are loaded at runtime" {
	run wp eval 'echo class_exists( "Plugin_Devkit" ) && class_exists( "Plugin_Devkit_Loader" ) ? "loaded" : "missing";'
	[ "$status" -eq 0 ]
	echo "$output" | grep -q 'loaded'
}

@test "public hooks run: stylesheet is enqueued on the homepage" {
	run curl -fsS "$WP_URL"
	[ "$status" -eq 0 ]
	echo "$output" | grep -q 'plugin-devkit-public.css'
}

@test "no php errors from the plugin in wordpress logs" {
	! dc logs wordpress 2>&1 | grep -Ei 'PHP (Fatal|Parse|Warning|Deprecated)' | grep plugin-devkit
}

@test "plugin-devkit deactivates cleanly" {
	run wp plugin deactivate plugin-devkit
	[ "$status" -eq 0 ]
	echo "$output" | grep -q 'Success:'
	run wp plugin activate plugin-devkit
	[ "$status" -eq 0 ]
}

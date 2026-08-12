<?php
/**
 * Plugin Devkit tooling
 *
 * A standalone CLI wizard that initializes a new plugin from this devkit by
 * renaming the plugin and replacing all of the template placeholders.
 *
 * Usage: php tools.php init
 *
 * @package Plugin_Devkit
 */

// phpcs:disable WordPress.WP.AlternativeFunctions, WordPress.Security.EscapeOutput -- Standalone CLI wizard: WordPress is not loaded, so the WP_Filesystem, wp_json_encode and escaping APIs are unavailable, and all output goes to a terminal.

/**
 * Entrypoint of the CLI wizard.
 *
 * @param array $argv Command line arguments.
 */
function main( $argv ) {
	$command = $argv[ count( $argv ) - 1 ];
	switch ( $command ) {
		case 'init':
			init();
			break;
		default:
			write_ln( 'Invalid command', 1 );
	}
}

/**
 * Interactively collect plugin metadata and apply it to the project.
 */
function init() {
	$plugin_name  = input( 'Plugin Name', 'Plugin Devkit' );
	$author       = input( 'Plugin Author', 'Clay Risser' );
	$contributors = input( 'Contributors', 'clayrisser' );
	$tags         = input( 'Tags', 'comments, spam' );
	$description  = input( 'Description', 'This is a short description of what the plugin does. It\'s displayed in the WordPress admin area.' );
	$version      = input( 'Version', '0.0.1' );
	$requires     = input( 'Requires at least (WordPress)', '6.4' );
	$tested       = input( 'Tested up to (WordPress)', '7.0' );
	$requires_php = input( 'Requires PHP', '8.1' );
	$license      = input( 'License', 'GPL-3.0-or-later' );
	$plugin_uri   = input( 'Plugin URI', 'https://wordpress.org/plugins/plugin-devkit/' );
	$author_uri   = input( 'Author URI', 'https://clayrisser.com/' );
	$license_uri  = input( 'License URI', 'https://www.gnu.org/licenses/gpl-3.0.html' );
	$donate_link  = input( 'Donate Link', 'https://clayrisser.com/donate' );
	find_and_replace_all( 'https://www.gnu.org/licenses/gpl-3.0.html', $license_uri );
	find_and_replace_all( 'https://wordpress.org/plugins/plugin-devkit/', $plugin_uri );
	find_and_replace_all( 'https://clayrisser.com/donate', $donate_link );
	find_and_replace_all( 'https://clayrisser.com/', $author_uri );
	find_and_replace_all( 'Clay Risser', $author );
	find_and_replace_all( 'comments, spam', $tags );
	find_and_replace_all( 'This is a short description of what the plugin does. It\'s displayed in the WordPress admin area.', $description );
	find_and_replace_all( '0.0.1', $version );
	find_and_replace_all( '6.4', $requires );
	find_and_replace_all( '7.0', $tested );
	find_and_replace_all( '8.1', $requires_php );
	find_and_replace_all( 'clayrisser', $contributors );
	find_and_replace_all( 'GPL-3.0-or-later', $license );
	name_plugin( $plugin_name );
}

/**
 * Prompt for a value, falling back to a default on empty input or EOF.
 *
 * @param string $tag      Prompt label.
 * @param string $fallback Default value when the user just presses enter.
 * @return string The value entered by the user, or the default.
 */
function input( $tag, $fallback ) {
	$value = read_ln( $tag . ' (' . $fallback . '): ' );
	if ( 'exit' === $value ) {
		exit( 0 );
	}
	return ( '' === $value ) ? $fallback : $value;
}

/**
 * Rename the plugin across file contents, file names and the plugin directory.
 *
 * @param string $name The new plugin name, in title case (e.g. "My Cool Plugin").
 */
function name_plugin( $name ) {
	$snake     = change_case( $name, 'snake' );
	$kebab     = change_case( $name, 'kebab' );
	$space     = change_case( $name, 'space' );
	$cap_snake = change_case( $name, 'cap_snake' );
	$cap_kebab = change_case( $name, 'cap_kebab' );
	$cap_space = change_case( $name, 'cap_space' );
	find_and_replace_all( 'plugin_devkit', $snake );
	find_and_replace_all( 'plugin-devkit', $kebab );
	find_and_replace_all( 'plugin devkit', $space );
	find_and_replace_all( 'Plugin_Devkit', $cap_snake );
	find_and_replace_all( 'Plugin-Devkit', $cap_kebab );
	find_and_replace_all( 'Plugin Devkit', $cap_space );
	find_and_replace_files( 'plugin_devkit', $snake );
	find_and_replace_files( 'plugin-devkit', $kebab );
	find_and_replace_files( 'Plugin_Devkit', $cap_snake, $kebab );
	find_and_replace_files( 'Plugin-Devkit', $cap_kebab, $kebab );
}

/**
 * Convert a title-case string to another case style.
 *
 * @param string $str  The string to convert.
 * @param string $to   Target case: snake, kebab, space, cap_snake, cap_kebab or cap_space.
 * @param string $from Source case (only title is supported).
 * @return string The converted string.
 */
function change_case( $str, $to, $from = 'title' ) {
	$str = trim( preg_replace( '/[\s\t\n\r\s]+/', ' ', $str ) );
	if ( 'title' === $from ) {
		switch ( $to ) {
			case 'snake':
				$str = strtolower( str_replace( ' ', '_', $str ) );
				break;
			case 'kebab':
				$str = str_replace( '_', '-', change_case( $str, 'snake' ) );
				break;
			case 'space':
				$str = str_replace( '_', ' ', change_case( $str, 'snake' ) );
				break;
			case 'cap_snake':
				$str = str_replace( ' ', '_', change_case( $str, 'cap_space' ) );
				break;
			case 'cap_kebab':
				$str = str_replace( ' ', '-', change_case( $str, 'cap_space' ) );
				break;
			case 'cap_space':
				$str = ucwords( change_case( $str, 'space' ) );
				break;
		}
	}
	return $str;
}

/**
 * Replace a string in the contents of every project file.
 *
 * @param string $find    The string to search for.
 * @param string $replace The replacement string.
 */
function find_and_replace_all( $find, $replace ) {
	if ( $find === $replace ) {
		return;
	}
	foreach ( project_files( '.' ) as $path ) {
		find_and_replace( $path, $find, $replace );
	}
}

/**
 * Rename files (and finally the root directory) whose name contains a string.
 *
 * @param string $find    The string to search for in file names.
 * @param string $replace The replacement string.
 * @param string $root    The directory to search, relative to the project root.
 */
function find_and_replace_files( $find, $replace, $root = 'plugin-devkit' ) {
	if ( $find !== $replace ) {
		foreach ( project_files( './' . $root ) as $path ) {
			$basename = basename( $path );
			if ( str_contains( $basename, $find ) ) {
				rename( $path, dirname( $path ) . '/' . str_replace( $find, $replace, $basename ) );
			}
		}
		if ( file_exists( getcwd() . '/' . $find ) ) {
			rename( getcwd() . '/' . $find, getcwd() . '/' . $replace );
		}
	}
}

/**
 * Recursively list the project files the wizard is allowed to rewrite.
 *
 * Skips VCS internals, dependency and state directories, binary assets, and
 * files whose contents must never be templated (LICENSE, composer.lock).
 *
 * @param string $root The directory to walk.
 * @return array Absolute paths of matching files.
 */
function project_files( $root ) {
	$skip_dirs       = array( '.git', '.make', 'vendor', 'node_modules', 'assets' );
	$skip_files      = array( 'LICENSE', 'composer.lock' );
	$skip_extensions = array( 'png', 'jpg', 'jpeg', 'gif', 'ico' );
	$directory       = new RecursiveDirectoryIterator( $root, RecursiveDirectoryIterator::SKIP_DOTS );
	$filter          = new RecursiveCallbackFilterIterator(
		$directory,
		function ( $file ) use ( $skip_dirs, $skip_files, $skip_extensions ) {
			if ( $file->isDir() ) {
				return ! in_array( $file->getFilename(), $skip_dirs, true );
			}
			if ( in_array( $file->getFilename(), $skip_files, true ) ) {
				return false;
			}
			return ! in_array( strtolower( $file->getExtension() ), $skip_extensions, true );
		}
	);
	$files           = array();
	foreach ( new RecursiveIteratorIterator( $filter ) as $file ) {
		if ( $file->isFile() ) {
			$files[] = $file->getRealPath();
		}
	}
	return $files;
}

/**
 * Replace a string in the contents of a single file.
 *
 * @param string $path    Path of the file to rewrite.
 * @param string $find    The string to search for.
 * @param string $replace The replacement string.
 */
function find_and_replace( $path, $find, $replace ) {
	$contents = file_get_contents( $path );
	if ( false === $contents || ! str_contains( $contents, $find ) ) {
		return;
	}
	file_put_contents( $path, str_replace( $find, $replace, $contents ) );
}

/**
 * Write a message to stdout, or to stderr when a non-zero code is given.
 *
 * @param string $message The message to write.
 * @param int    $code    Exit code; non-zero writes to stderr and exits.
 * @return int The exit code.
 */
function write( $message, $code = 0 ) {
	if ( 0 === $code ) {
		fwrite( STDOUT, $message );
		return $code;
	}
	fwrite( STDERR, $message );
	exit( $code );
}

/**
 * Write a message followed by a newline.
 *
 * @param string $message The message to write.
 * @param int    $code    Exit code; non-zero writes to stderr and exits.
 * @return int The exit code.
 */
function write_ln( $message, $code = 0 ) {
	return write( $message . "\n", $code );
}

/**
 * Read a line from stdin, returning an empty string on EOF.
 *
 * @param string $message Optional prompt to print first.
 * @return string The line read, without the trailing newline.
 */
function read_ln( $message ) {
	if ( $message ) {
		write( $message );
	}
	$stdin = fgets( STDIN );
	if ( false === $stdin ) {
		write( "\n" );
		return '';
	}
	return rtrim( $stdin, "\r\n" );
}

main( $argv );

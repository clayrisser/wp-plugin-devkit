<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Devkit
 * @subpackage Plugin_Devkit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin devkit, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Devkit
 * @subpackage Plugin_Devkit/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Devkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_devkit    The ID of this plugin.
	 */
	private $plugin_devkit;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_devkit       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_devkit, $version ) {

		$this->plugin_devkit = $plugin_devkit;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Devkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Devkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_devkit, plugin_dir_url( __FILE__ ) . 'css/plugin-devkit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Devkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Devkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_devkit, plugin_dir_url( __FILE__ ) . 'js/plugin-devkit-admin.js', array( 'jquery' ), $this->version, false );

	}

}

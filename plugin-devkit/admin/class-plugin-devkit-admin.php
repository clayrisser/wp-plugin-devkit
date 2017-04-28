<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.0.1
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
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

  /**
   * The options name to be used in this plugin
   *
   * @since  0.0.1
   * @access private
   * @var  string $option_name Option name of this plugin
   */
  private $option_name = 'plugin_devkit';

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.0.1
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-devkit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.0.1
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-devkit-admin.js', array( 'jquery' ), $this->version, false );

	}

  /**
   * Add an options page under the Settings submenu
   *
   * @since  0.0.1
   */
  public function add_options_page() {
    $this->options_page = add_options_page(
      __( 'Plugin Devkit Settings', 'plugin-devkit' ),
      __( 'Plugin Devkit', 'plugin-devkit' ),
      'manage_options',
      $this->plugin_name,
      array( $this, 'display_options_page' )
    );
  }

  /**
   * Render the options page for plugin
   *
   * @since  0.0.1
   */
  public function display_options_page() {
    include_once 'partials/plugin-devkit-admin-display.php';
  }

  /**
   * Register all related settings of this plugin
   *
   * @since  0.0.1
   */
  public function register_settings() {
    add_settings_section(
      $this->option_name . '_general',
      __( 'General', 'plugin-devkit' ),
      array( $this, $this->option_name . '_general_cb' ),
      $this->plugin_name
    );
    add_settings_field(
      $this->option_name . '_fav_text_editor',
      __( 'Favorite Text Editor', 'plugin-devkit'),
      array( $this, $this->option_name . '_fav_text_editor_cb' ),
      $this->plugin_name,
      $this->option_name . '_general',
      array( 'label_for' => $this->option_name . '_fav_text_editor' )
    );
    register_setting(
      $this->plugin_name,
      $this->option_name . '_fav_text_editor',
      array( $this, $this->option_name . '_sanitize_fav_text_editor' )
    );
  }

  /**
   * Render the text for the general section
   *
   * @since  0.0.1
   */
  public function plugin_devkit_general_cb() {
    echo '<p>' . __( 'Please change the settings accordingly.', 'plugin-devkit' ) . '</p>';
  }

  /**
   * Render the radio input field for fav text editor option
   *
   * @since  0.0.1
   */
  public function plugin_devkit_fav_text_editor_cb() {
    $option_name = $this->option_name . '_fav_text_editor';
    $fav_text_editor = get_option( $option_name );
    ?>
      <fieldset>
        <label>
          <input type="radio" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="vim" <?php checked($fav_text_editor, 'vim'); ?>>
          <?php _e( 'Vim', 'plugin-devkit' ); ?>
        </label>
        <br />
        <label>
          <input type="radio" name="<?php echo $option_name; ?>" value="emacs" <?php checked($fav_text_editor, 'emacs'); ?>>
          <?php _e( 'Emacs', 'plugin-devkit' ); ?>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Sanitize the fav text editor value before being saved to database
   *
   * @param  string $fav_text_editor $_POST value
   * @since  0.0.1
   * @return string           Sanitized value
   */
  public function plugin_devkit_sanitize_fav_text_editor( $fav_text_editor ) {
    if ( in_array( $fav_text_editor, array( 'vim', 'emacs' ), true ) ) {
      return $fav_text_editor;
    }
  }
}

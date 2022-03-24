<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Widget_Calculator
 * @subpackage Widget_Calculator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Widget_Calculator
 * @subpackage Widget_Calculator/includes
 * @author     Your Name <email@example.com>
 */
class Widget_Calculator {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Widget_Calculator_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $widget_calculator    The string used to uniquely identify this plugin.
	 */
	protected $widget_calculator;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->widget_calculator = 'widget-calculator';

		// Define pack sizes available:
		$this->pack_inventory = array(
			250, 
			500,
			1000,
			2000,
			5000
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->register_shortcodes();

	}
	
	/**
	* Register Shortcodes:
	*/
	private function register_shortcodes() {
		add_shortcode('widget-calculator', array( &$this, 'display_calculator'));
	}

	/**
	 * Display the calculator:
	 */
	function display_calculator($atts = [], $content = null) {
		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/widget-calculator-public-display.php';
		return ob_get_clean();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Widget_Calculator_Loader. Orchestrates the hooks of the plugin.
	 * - Widget_Calculator_i18n. Defines internationalization functionality.
	 * - Widget_Calculator_Admin. Defines all hooks for the admin area.
	 * - Widget_Calculator_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-widget-calculator-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-widget-calculator-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-widget-calculator-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-widget-calculator-public.php';

		$this->loader = new Widget_Calculator_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Widget_Calculator_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Widget_Calculator_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Widget_Calculator_Admin( $this->get_widget_calculator(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Widget_Calculator_Public( $this->get_widget_calculator(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		add_action( 'wp_ajax_nopriv_request_calculation', array( $this, 'request_calculation' ) );
    	add_action( 'wp_ajax_request_calculation', array( $this, 'request_calculation' ) );

	}

	/**
	 * Ajax user request -
	 * Perform calculation based on requested quantity,
	 * and the stored inventory
	 */
	public function request_calculation() {
		$data = $_POST;

		$quantity_requested = (int)$data['user_quantity'];
		$remainder = $quantity_requested;
		$ship_packs = array();
		$packs_fulfillment = '';

		rsort($this->pack_inventory);
			
		// Prepare the pack sizes:
		foreach ( $this->pack_inventory as $key => $value ) {

			$divisor = floor($remainder / $value);
			$ship_packs[$value] = $divisor;
			$remainder -= $value * $divisor;

		}

		// Account for the remainder:
		asort($this->pack_inventory);

		while ( $remainder > 0 ) {

			foreach ( $ship_packs as $k => $v ) {
				if ($k >= $remainder) {
					$ship_packs[$value] += 1;
					$remainder -= $k;
					break;
				}
			}
			
			// Combine smaller packs by removing duplicates and adding to next size up:
			if ($ship_packs[250] > 1) {
				$ship_packs[250] = 0;
				$ship_packs[500] += 1;
			}
			if ($ship_packs[500] > 1) {
				$ship_packs[500] = 0;
				$ship_packs[1000] += 1;
			}
		}

		// Build our output string:
		foreach ($ship_packs as $kk => $vv) {
			if ($vv > 0) {
				$packs_fulfillment .= '<p>' . $kk . ' x ' . $vv . '</p>';
			}
		}

		$responseData = array(
			'success' => true,
			'requested_quantity' => $quantity_requested,
			'packs_fulfillment' => $packs_fulfillment
		);

		wp_send_json_success( __( $responseData ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_widget_calculator() {
		return $this->widget_calculator;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Widget_Calculator_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

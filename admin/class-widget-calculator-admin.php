<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Widget_Calculator
 * @subpackage Widget_Calculator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Widget_Calculator
 * @subpackage Widget_Calculator/admin
 * @author     Your Name <email@example.com>
 */
class Widget_Calculator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $widget_calculator    The ID of this plugin.
	 */
	private $widget_calculator;

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
	 * @param      string    $widget_calculator       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $widget_calculator, $version ) {

		$this->widget_calculator = $widget_calculator;
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
		 * defined in Widget_Calculator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Widget_Calculator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->widget_calculator, plugin_dir_url( __FILE__ ) . 'css/widget-calculator-admin.css', array(), $this->version, 'all' );

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
		 * defined in Widget_Calculator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Widget_Calculator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->widget_calculator, plugin_dir_url( __FILE__ ) . 'js/widget-calculator-admin.js', array( 'jquery' ), $this->version, false );

	}

}

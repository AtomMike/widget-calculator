<?php

/**
 * Widget Calculator
 *
 * @link              http://wallys-widgets.com
 * @since             1.0.0
 * @package           Widget_Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Widget Calculator
 * Plugin URI:        https://wallys-widgets.com
 * Description:       This tool allows WIaF to calculate the optimum widget-whack per order
 * Version:           1.0.0
 * Author:            Mike Bailey
 * Author URI:        https://mike-bailey.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       widget-calculator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WIDGET_CALCULATOR_VERSION', '1.0.0' );

// Activation
function activate_widget_calculator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-widget-calculator-activator.php';
	Widget_Calculator_Activator::activate();
}

function deactivate_widget_calculator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-widget-calculator-deactivator.php';
	Widget_Calculator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_widget_calculator' );
register_deactivation_hook( __FILE__, 'deactivate_widget_calculator' );

require plugin_dir_path( __FILE__ ) . 'includes/class-widget-calculator.php';

// Begin execution
function run_widget_calculator() {

	$plugin = new Widget_Calculator();
	$plugin->run();

}
run_widget_calculator();

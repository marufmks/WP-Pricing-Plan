<?php
/**
 * Plugin Name:     Pricing Plan
 * Plugin URI:      https://www.github.com/marufmks
 * Description:     Pricing Plan is a plugin for WordPress that allows you to create and manage pricing plans for your products and services.
 * Author:          Maruf Khan
 * Author URI:      https://www.github.com/marufmks
 * Text Domain:     pricing-plan
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package mrkwp
 */

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'No Access!' );

// Define plugin version
define( 'PRICING_PLAN_VERSION', '1.0.0' );
define('PRICING_PLAN_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('PRICING_PLAN_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

// Require once the Composer Autoload.
if ( file_exists( PRICING_PLAN_PLUGIN_PATH . '/lib/autoload.php' ) ) {
	require_once PRICING_PLAN_PLUGIN_PATH . '/lib/autoload.php';
}

/**
 * The code that runs during plugin activation.
 *
 * @return void
 */
function activate_pricing_plan_plugin() {
	Pricing_Plan\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_pricing_plan_plugin' );

/**
 * The code that runs during plugin deactivation.
 *
 * @return void
 */
function deactivate_pricing_plan_plugin() {
	Pricing_Plan\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_pricing_plan_plugin' );

/**
 * Initialize all the core classes of the plugin.
 */
if ( class_exists( 'Pricing_Plan\\Init' ) ) {
	Pricing_Plan\Init::register_services();
}
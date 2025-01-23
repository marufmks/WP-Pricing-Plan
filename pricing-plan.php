<?php
/**
 * Plugin Name: Pricing Plan
 * Plugin URI: https://github.com/pricing-plan
 * Description: Create and manage beautiful pricing plans with an easy-to-use interface
 * Version: 1.0.0
 * Author: Maruf Khan
 * Author URI: https://github.com/marufmks
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pricing-plan
 * Domain Path: /languages
 * 
 * @package Pricing_Plan
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Require once the Composer autoload
if (file_exists(dirname(__FILE__) . '/lib/autoload.php')) {
    require_once dirname(__FILE__) . '/lib/autoload.php';
}

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Pricing_Plan\\Init')) {
    Pricing_Plan\Init::init();
}

/**
 * Register activation and deactivation hooks
 */
register_activation_hook(__FILE__, function() {
	if (!class_exists('Pricing_Plan\Base\Activate')) {
		Pricing_Plan\Base\Activate::activate();
	}
});

register_deactivation_hook(__FILE__, function() {
	if (!class_exists('Pricing_Plan\Base\Deactivate')) {
		Pricing_Plan\Base\Deactivate::deactivate();
	}
});

register_uninstall_hook(__FILE__, function() {
	if (!class_exists('Pricing_Plan\Base\Uninstall')) {
		Pricing_Plan\Base\Uninstall::uninstall();
	}
});
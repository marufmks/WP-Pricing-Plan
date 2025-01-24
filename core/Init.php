<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan;

/**
 * The core plugin class that handles all service registration
 */
final class Init {
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() {
		return [
			Admin\AdminHooks::class,
			Api\PricingPlanController::class,
			// Add more service classes here
		];
	}

	/**
	 * Register all classes by calling their register_services() method if it exists
	 * @return void
	 */
	public static function register_services() {
		foreach (self::get_services() as $class) {
			if (method_exists($class, 'register_services')) {
				$class::register_services();
			}
		}
	}

	/**
	 * Initialize the plugin
	 * @return void
	 */
	public static function init() {
		// Define plugin constants
		self::define_constants();
		
		// Load plugin textdomain
		add_action('plugins_loaded', function() {
			load_plugin_textdomain(
				'pricing-plan',
				false,
				dirname(plugin_basename(PRICING_PLAN_PLUGIN_PATH)) . '/languages'
			);
		});
		
		// Register services
		self::register_services();
	}

	/**
	 * Define plugin constants
	 * @return void
	 */
	private static function define_constants() {
		if (!defined('PRICING_PLAN_VERSION')) {
			define('PRICING_PLAN_VERSION', '1.0.0');
		}
		if (!defined('PRICING_PLAN_PLUGIN_PATH')) {
			define('PRICING_PLAN_PLUGIN_PATH', plugin_dir_path(dirname(__FILE__)));
		}
		if (!defined('PRICING_PLAN_PLUGIN_URL')) {
			define('PRICING_PLAN_PLUGIN_URL', plugin_dir_url(dirname(__FILE__)));
		}
	}
}
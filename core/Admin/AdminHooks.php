<?php

/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Admin;

/**
 * Class AdminHooks
 * Handles all admin-related hooks and callbacks
 * 
 * @package Pricing_Plan\Admin
 */
class AdminHooks
{
	/**
	 * Register admin hooks and services
	 *
	 * @return void
	 */
	public static function register_services()
	{
		$admin = new self();
		add_action('admin_menu', array($admin, 'add_admin_page'));
		add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
	}

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Initialize any necessary properties
	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_scripts()
	{
		// Add version and dependencies
		$asset_file = include PRICING_PLAN_PLUGIN_PATH . 'build/index.asset.php';
		
		wp_enqueue_script(
			'pricing-plan-admin-script',
			PRICING_PLAN_PLUGIN_URL . 'build/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		// Add admin styles
		wp_enqueue_style(
			'pricing-plan-admin-style',
			PRICING_PLAN_PLUGIN_URL . 'build/index.css',
			array(),
			$asset_file['version']
		);
	}

	/**
	 * Add admin menu page
	 *
	 * @return void
	 */
	public function add_admin_page()
	{
		add_menu_page(
			__('Pricing Plan', 'pricing-plan'),
			__('Pricing Plan', 'pricing-plan'),
			'manage_options',
			'pricing-plan',
			array($this, 'render_admin_page'),
			'dashicons-money-alt',
			25
		);

		// Add submenu pages
		add_submenu_page(
			'pricing-plan',
			__('All Plans', 'pricing-plan'),
			__('All Plans', 'pricing-plan'),
			'manage_options',
			'pricing-plan',
			array($this, 'render_admin_page')
		);
	}

	/**
	 * Render admin page content
	 *
	 * @return void
	 */
	public function render_admin_page()
	{
		echo '<div class="wrap">';
		echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
		echo '<div id="pricing-plan-admin-root"></div>';
		echo '</div>';
	}
}
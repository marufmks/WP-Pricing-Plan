<?php

namespace Pricing_Plan\Admin;

class AdminHooks
{
	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script('pricing-plan-admin-script', PRICING_PLAN_PLUGIN_URL . 'build/index.js', array('wp-element'), PRICING_PLAN_VERSION, true);
	}

	public function add_admin_page()
	{
		add_menu_page('Pricing Plan', 'Pricing Plan', 'manage_options', 'pricing-plan', array($this, 'render_admin_page'), 'dashicons-admin-generic', 2);
	}

	public function render_admin_page()
	{
		echo '<div id="pricing-plan-admin-root"></div>';
	}
}
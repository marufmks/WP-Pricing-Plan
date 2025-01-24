<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

class Deactivate
{
	/**
	 * Plugin deactivation hook callback
	 */
	public static function deactivate() {
		flush_rewrite_rules();
		
		// Add any additional cleanup code for deactivation
	}
}
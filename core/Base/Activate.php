<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

use Pricing_Plan\Base\Database;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();
		Database::create_tables();
		
		if (get_option('pricing_plan_version') === false) {
			add_option('pricing_plan_version', PRICING_PLAN_VERSION);
		} else {
			update_option('pricing_plan_version', PRICING_PLAN_VERSION);
		}
	}
}
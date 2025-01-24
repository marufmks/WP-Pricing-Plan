<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

use Pricing_Plan\Base\Database;

class Uninstall
{
	/**
	 * Plugin uninstall hook callback
	 */
	public static function uninstall()
	{
		// Drop plugin tables
		Database::drop_tables();
		
		// Delete plugin options
		delete_option('pricing_plan_version');
		
		// Clean up any other plugin data
		// Add any additional cleanup code here
	}
}
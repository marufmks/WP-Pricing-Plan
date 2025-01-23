<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan;

class Init
{
	public static function register_services()
	{
		\Pricing_Plan\Admin\AdminHooks::register_services();
		
	}
}
<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

class Uninstall
{
	public static function uninstall()
	{
		flush_rewrite_rules();
	}
}
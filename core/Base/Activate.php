<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
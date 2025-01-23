<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Base;

class Database {
    /**
     * Create plugin tables
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Pricing Plans Table
        $pricing_plans_table = $wpdb->prefix . 'pricing_plans';
        $sql_pricing_plans = "CREATE TABLE IF NOT EXISTS $pricing_plans_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            type varchar(20) NOT NULL DEFAULT 'single',
            name varchar(255) NOT NULL,
            description text,
            status varchar(20) DEFAULT 'draft',
            settings longtext,
            created_by bigint(20) unsigned,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Pricing Packages Table
        $pricing_packages_table = $wpdb->prefix . 'pricing_packages';
        $sql_pricing_packages = "CREATE TABLE IF NOT EXISTS $pricing_packages_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            plan_id bigint(20) unsigned NOT NULL,
            title varchar(255) NOT NULL,
            description text,
            price_monthly decimal(10,2),
            price_yearly decimal(10,2),
            price_lifetime decimal(10,2),
            price_onetime decimal(10,2),
            discount_amount decimal(10,2),
            discount_type varchar(20),
            badge varchar(50),
            features longtext,
            button_text varchar(100),
            button_url varchar(255),
            recommended tinyint(1) DEFAULT 0,
            sort_order int DEFAULT 0,
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY plan_id (plan_id),
            CONSTRAINT fk_pricing_plan
                FOREIGN KEY (plan_id) 
                REFERENCES $pricing_plans_table(id)
                ON DELETE CASCADE
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_pricing_plans);
        dbDelta($sql_pricing_packages);
    }

    /**
     * Drop plugin tables
     */
    public static function drop_tables() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pricing_packages");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pricing_plans");
    }
} 
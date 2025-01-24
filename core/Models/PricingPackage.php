<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Models;

class PricingPackage {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'pricing_packages';
    }

    /**
     * Get packages by plan ID
     */
    public function get_by_plan($plan_id, $args = []) {
        $defaults = [
            'status' => 'active',
            'orderby' => 'sort_order',
            'order' => 'ASC',
        ];

        $args = wp_parse_args($args, $defaults);
        $where = ['plan_id = %d'];
        
        if (!empty($args['status'])) {
            $where[] = $this->wpdb->prepare('status = %s', $args['status']);
        }

        $where = implode(' AND ', $where);
        $sql = "SELECT * FROM {$this->table_name} WHERE {$where} ORDER BY {$args['orderby']} {$args['order']}";
        
        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, $plan_id)
        );
    }

    /**
     * Get a single package
     */
    public function get($id) {
        $package = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id)
        );

        if ($package && !empty($package->features)) {
            $package->features = json_decode($package->features);
        }

        return $package;
    }

    /**
     * Create a new package
     */
    public function create($data) {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = wp_json_encode($data['features']);
        }

        $inserted = $this->wpdb->insert(
            $this->table_name,
            $data,
            [
                '%d', // plan_id
                '%s', // title
                '%s', // description
                '%f', // price_monthly
                '%f', // price_yearly
                '%f', // price_lifetime
                '%f', // price_onetime
                '%f', // discount_amount
                '%s', // discount_type
                '%s', // badge
                '%s', // features
                '%s', // button_text
                '%s', // button_url
                '%d', // recommended
                '%d', // sort_order
                '%s', // status
            ]
        );

        if ($inserted) {
            return $this->wpdb->insert_id;
        }

        return false;
    }

    /**
     * Update a package
     */
    public function update($id, $data) {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = wp_json_encode($data['features']);
        }

        $updated = $this->wpdb->update(
            $this->table_name,
            $data,
            ['id' => $id],
            null,
            ['%d']
        );

        return $updated !== false;
    }

    /**
     * Delete a package
     */
    public function delete($id) {
        return $this->wpdb->delete(
            $this->table_name,
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Update package order
     */
    public function update_order($id, $order) {
        return $this->wpdb->update(
            $this->table_name,
            ['sort_order' => $order],
            ['id' => $id],
            ['%d'],
            ['%d']
        );
    }
} 
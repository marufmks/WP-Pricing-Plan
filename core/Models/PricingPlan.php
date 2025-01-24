<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Models;

class PricingPlan {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'pricing_plans';
    }

    /**
     * Get all pricing plans
     */
    public function get_all($args = []) {
        $defaults = [
            'status' => 'publish',
            'orderby' => 'created_at',
            'order' => 'DESC',
            'limit' => 10,
            'offset' => 0,
        ];

        $args = wp_parse_args($args, $defaults);
        $where = ['1=1'];
        
        if (!empty($args['status'])) {
            $where[] = $this->wpdb->prepare('status = %s', $args['status']);
        }

        $where = implode(' AND ', $where);
        $sql = "SELECT * FROM {$this->table_name} WHERE {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d";
        
        return $this->wpdb->get_results(
            $this->wpdb->prepare($sql, $args['limit'], $args['offset'])
        );
    }

    /**
     * Get a single plan
     */
    public function get($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id)
        );
    }

    /**
     * Create a new plan
     */
    public function create($data) {
        $defaults = [
            'type' => 'single',
            'status' => 'draft',
            'created_by' => get_current_user_id(),
        ];

        $data = wp_parse_args($data, $defaults);
        
        if (isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = wp_json_encode($data['settings']);
        }

        $inserted = $this->wpdb->insert(
            $this->table_name,
            $data,
            ['%s', '%s', '%s', '%s', '%s', '%d']
        );

        if ($inserted) {
            return $this->wpdb->insert_id;
        }

        return false;
    }

    /**
     * Update a plan
     */
    public function update($id, $data) {
        if (isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = wp_json_encode($data['settings']);
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
     * Delete a plan
     */
    public function delete($id) {
        return $this->wpdb->delete(
            $this->table_name,
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Get plan with packages
     */
    public function get_with_packages($id) {
        $plan = $this->get($id);
        if (!$plan) {
            return null;
        }

        $package_model = new PricingPackage();
        $plan->packages = $package_model->get_by_plan($id);
        
        return $plan;
    }
}

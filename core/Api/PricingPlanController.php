<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Api;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;
use Pricing_Plan\Models\PricingPlan;
use Pricing_Plan\Models\PricingPackage;

/**
 * Class PricingPlanController
 * Handles REST API endpoints for pricing plans
 */
class PricingPlanController extends WP_REST_Controller {
    private $plan_model;
    private $package_model;

    /**
     * Register REST API services
     */
    public static function register_services() {
        $controller = new self();
        add_action('rest_api_init', [$controller, 'register_routes']);
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = 'pricing-plan/v1';
        $this->rest_base = 'plans';
        $this->plan_model = new PricingPlan();
        $this->package_model = new PricingPackage();
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Plans endpoints
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [$this, 'create_item'],
                    'permission_callback' => [$this, 'create_item_permissions_check'],
                    'args'                => $this->get_endpoint_args_for_item_schema(true),
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_item'],
                    'permission_callback' => [$this, 'get_item_permissions_check'],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [$this, 'update_item'],
                    'permission_callback' => [$this, 'update_item_permissions_check'],
                    'args'                => $this->get_endpoint_args_for_item_schema(false),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [$this, 'delete_item'],
                    'permission_callback' => [$this, 'delete_item_permissions_check'],
                ],
            ]
        );

        // Packages endpoints
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<plan_id>[\d]+)/packages',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_packages'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [$this, 'create_package'],
                    'permission_callback' => [$this, 'create_item_permissions_check'],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<plan_id>[\d]+)/packages/(?P<id>[\d]+)',
            [
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [$this, 'update_package'],
                    'permission_callback' => [$this, 'update_item_permissions_check'],
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [$this, 'delete_package'],
                    'permission_callback' => [$this, 'delete_item_permissions_check'],
                ],
            ]
        );
    }

    /**
     * Get a collection of items
     */
    public function get_items($request) {
        $args = [
            'status' => $request['status'] ?? 'publish',
            'limit'  => $request['per_page'] ?? 10,
            'offset' => ($request['page'] ?? 1) - 1,
        ];

        $items = $this->plan_model->get_all($args);
        return rest_ensure_response($items);
    }

    /**
     * Get one item from the collection
     */
    public function get_item($request) {
        $id = (int) $request['id'];
        $item = $this->plan_model->get_with_packages($id);

        if (!$item) {
            return new WP_Error(
                'rest_not_found',
                __('Plan not found.', 'pricing-plan'),
                ['status' => 404]
            );
        }

        return rest_ensure_response($item);
    }

    /**
     * Create one item from the collection
     */
    public function create_item($request) {
        $plan_data = [
            'name'        => $request['name'],
            'type'        => $request['type'] ?? 'single',
            'description' => $request['description'] ?? '',
            'settings'    => $request['settings'] ?? [],
            'status'      => $request['status'] ?? 'draft',
        ];

        $plan_id = $this->plan_model->create($plan_data);

        if (!$plan_id) {
            return new WP_Error(
                'rest_cannot_create',
                __('Failed to create plan.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        $plan = $this->plan_model->get($plan_id);
        return rest_ensure_response($plan);
    }

    /**
     * Update one item from the collection
     */
    public function update_item($request) {
        $id = (int) $request['id'];
        $plan_data = [
            'name'        => $request['name'],
            'type'        => $request['type'],
            'description' => $request['description'],
            'settings'    => $request['settings'],
            'status'      => $request['status'],
        ];

        $updated = $this->plan_model->update($id, $plan_data);

        if (!$updated) {
            return new WP_Error(
                'rest_cannot_update',
                __('Failed to update plan.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        $plan = $this->plan_model->get($id);
        return rest_ensure_response($plan);
    }

    /**
     * Delete one item from the collection
     */
    public function delete_item($request) {
        $id = (int) $request['id'];
        $deleted = $this->plan_model->delete($id);

        if (!$deleted) {
            return new WP_Error(
                'rest_cannot_delete',
                __('Failed to delete plan.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        return rest_ensure_response(['deleted' => true]);
    }

    /**
     * Get packages for a plan
     */
    public function get_packages($request) {
        $plan_id = (int) $request['plan_id'];
        $packages = $this->package_model->get_by_plan($plan_id);
        return rest_ensure_response($packages);
    }

    /**
     * Create a package
     */
    public function create_package($request) {
        $package_data = [
            'plan_id'         => (int) $request['plan_id'],
            'title'           => $request['title'],
            'description'     => $request['description'] ?? '',
            'price_monthly'   => $request['price_monthly'] ?? null,
            'price_yearly'    => $request['price_yearly'] ?? null,
            'price_lifetime'  => $request['price_lifetime'] ?? null,
            'price_onetime'   => $request['price_onetime'] ?? null,
            'discount_amount' => $request['discount_amount'] ?? null,
            'discount_type'   => $request['discount_type'] ?? null,
            'badge'           => $request['badge'] ?? null,
            'features'        => $request['features'] ?? [],
            'button_text'     => $request['button_text'] ?? 'Get Started',
            'button_url'      => $request['button_url'] ?? '',
            'recommended'     => $request['recommended'] ?? 0,
            'sort_order'      => $request['sort_order'] ?? 0,
        ];

        $package_id = $this->package_model->create($package_data);

        if (!$package_id) {
            return new WP_Error(
                'rest_cannot_create',
                __('Failed to create package.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        $package = $this->package_model->get($package_id);
        return rest_ensure_response($package);
    }

    /**
     * Update a package
     */
    public function update_package($request) {
        $id = (int) $request['id'];
        $package_data = [
            'title'           => $request['title'],
            'description'     => $request['description'],
            'price_monthly'   => $request['price_monthly'],
            'price_yearly'    => $request['price_yearly'],
            'price_lifetime'  => $request['price_lifetime'],
            'price_onetime'   => $request['price_onetime'],
            'discount_amount' => $request['discount_amount'],
            'discount_type'   => $request['discount_type'],
            'badge'           => $request['badge'],
            'features'        => $request['features'],
            'button_text'     => $request['button_text'],
            'button_url'      => $request['button_url'],
            'recommended'     => $request['recommended'],
            'sort_order'      => $request['sort_order'],
        ];

        $updated = $this->package_model->update($id, $package_data);

        if (!$updated) {
            return new WP_Error(
                'rest_cannot_update',
                __('Failed to update package.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        $package = $this->package_model->get($id);
        return rest_ensure_response($package);
    }

    /**
     * Delete a package
     */
    public function delete_package($request) {
        $id = (int) $request['id'];
        $deleted = $this->package_model->delete($id);

        if (!$deleted) {
            return new WP_Error(
                'rest_cannot_delete',
                __('Failed to delete package.', 'pricing-plan'),
                ['status' => 500]
            );
        }

        return rest_ensure_response(['deleted' => true]);
    }

    /**
     * Check if a given request has access to get items
     */
    public function get_items_permissions_check($request) {
        return current_user_can('manage_options');
    }

    /**
     * Check if a given request has access to create items
     */
    public function create_item_permissions_check($request) {
        return current_user_can('manage_options');
    }

    /**
     * Check if a given request has access to update a specific item
     */
    public function update_item_permissions_check($request) {
        return current_user_can('manage_options');
    }

    /**
     * Check if a given request has access to delete a specific item
     */
    public function delete_item_permissions_check($request) {
        return current_user_can('manage_options');
    }
}

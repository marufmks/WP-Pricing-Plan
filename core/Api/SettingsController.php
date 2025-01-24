<?php
/**
 * @package Pricing_Plan
 */
namespace Pricing_Plan\Api;

use WP_REST_Controller;
use WP_REST_Server;
use WP_Error;

/**
 * Class SettingsController
 * Handles REST API endpoints for plugin settings
 */
class SettingsController extends WP_REST_Controller {
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
        $this->rest_base = 'settings';
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_settings'],
                    'permission_callback' => [$this, 'get_settings_permissions_check'],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [$this, 'update_settings'],
                    'permission_callback' => [$this, 'update_settings_permissions_check'],
                    'args'                => [
                        'currency_symbol' => [
                            'type' => 'string',
                            'required' => false,
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                        'currency_position' => [
                            'type' => 'string',
                            'required' => false,
                            'enum' => ['before', 'after'],
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                        'default_duration' => [
                            'type' => 'string',
                            'required' => false,
                            'enum' => ['monthly', 'yearly', 'lifetime'],
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                        'enable_custom_css' => [
                            'type' => 'boolean',
                            'required' => false,
                        ],
                        'custom_css' => [
                            'type' => 'string',
                            'required' => false,
                            'sanitize_callback' => 'sanitize_textarea_field',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Get settings
     */
    public function get_settings($request) {
        $settings = get_option('pricing_plan_settings', [
            'currency_symbol' => '$',
            'currency_position' => 'before',
            'default_duration' => 'monthly',
            'enable_custom_css' => false,
            'custom_css' => '',
        ]);

        return rest_ensure_response($settings);
    }

    /**
     * Update settings
     */
    public function update_settings($request) {
        try {
            $new_settings = $this->prepare_item_for_database($request);
            
            // Get existing settings
            $existing_settings = get_option('pricing_plan_settings', [
                'currency_symbol' => '$',
                'currency_position' => 'before',
                'default_duration' => 'monthly',
                'enable_custom_css' => false,
                'custom_css' => '',
            ]);

            // Merge existing settings with new settings
            $settings = array_merge($existing_settings, $new_settings);
            
            // Delete the option first to ensure update works even if values haven't changed
            delete_option('pricing_plan_settings');
            $updated = add_option('pricing_plan_settings', $settings, '', 'no');

            // If add_option returns false, it might be because the option already exists
            if (!$updated) {
                $updated = update_option('pricing_plan_settings', $settings);
            }

            // Even if update_option returns false (no changes), we still want to return success
            // as long as the settings exist and match what we want
            $current_settings = get_option('pricing_plan_settings');
            if ($current_settings === $settings) {
                return rest_ensure_response($settings);
            }

            error_log('Failed to update pricing plan settings: ' . print_r($settings, true));
            return new WP_Error(
                'rest_cannot_update',
                __('Failed to update settings.', 'pricing-plan'),
                ['status' => 500]
            );
        } catch (\Exception $e) {
            error_log('Exception while updating pricing plan settings: ' . $e->getMessage());
            return new WP_Error(
                'rest_cannot_update',
                __('Failed to update settings due to an error.', 'pricing-plan'),
                ['status' => 500]
            );
        }
    }

    /**
     * Prepare settings for database
     */
    protected function prepare_item_for_database($request) {
        $settings = [];
        $params = $request->get_json_params();

        if (isset($params['currency_symbol'])) {
            $settings['currency_symbol'] = sanitize_text_field($params['currency_symbol']);
        }

        if (isset($params['currency_position'])) {
            $settings['currency_position'] = sanitize_text_field($params['currency_position']);
        }

        if (isset($params['default_duration'])) {
            $settings['default_duration'] = sanitize_text_field($params['default_duration']);
        }

        if (isset($params['enable_custom_css'])) {
            $settings['enable_custom_css'] = (bool) $params['enable_custom_css'];
        }

        if (isset($params['custom_css'])) {
            $settings['custom_css'] = sanitize_textarea_field($params['custom_css']);
        }

        return $settings;
    }

    /**
     * Check if a given request has access to get settings
     */
    public function get_settings_permissions_check($request) {
        return current_user_can('manage_options');
    }

    /**
     * Check if a given request has access to update settings
     */
    public function update_settings_permissions_check($request) {
        return current_user_can('manage_options');
    }
} 
<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Custom_Post_Types' ) ) {

	class REST_API_Toolbox_Settings_Custom_Post_Types extends REST_API_Toolbox_Settings_Base {

		static $settings_key  = 'rest-api-toolbox-settings-cpt';

		/**
		 * Hook up WordPress actions and filters.
		 *
		 * @return void
		 */
		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_cpt_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( __CLASS__, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ self::$settings_key ] = __( 'Custom Post Types', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_cpt_settings() {
			$key = self::$settings_key;

			register_setting( $key, $key, array( __CLASS__, 'sanitize_cpt_settings') );

			$section_remove = 'cpt-remove';
			$section_auth = 'cpt-authentication';

			$namespace = REST_API_Toolbox_Common::core_namespace();

			// Get the list of custom post types.
			$post_types = REST_API_Toolbox_Common::get_custom_post_types();

			// Build the list of endpoints based on each post type's rest_base.
			$endpoints = array();
			foreach( $post_types as $post_type_object ) {
				$endpoints[] = ! empty( $post_type_object->rest_base ) ? $post_type_object->rest_base : $post_type_object->name;
			}

			// Are there custom post types available?
			if ( empty( $endpoints ) ) {

				// Create an empty section for No Custom Post Types
				add_settings_section( $section_remove, '', array( __CLASS__, 'section_header_no_cpts' ), $key );
			} else {

				// Create sections for remove and require authentication.
				add_settings_section( $section_remove, '', array( __CLASS__, 'section_header_remove' ), $key );
				add_settings_section( $section_auth, '', array( __CLASS__, 'section_header_require_authentication' ), $key );
			}

			foreach( $endpoints as $endpoint ) {

				// Add yes/no options to remove the endpoint.
				$name = 'remove-endpoint|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( '%s', 'rest-api-toolbox' ), $endpoint),
					array( __CLASS__, 'settings_checkbox' ),
					$key,
					$section_remove,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);

				// Add yes/no options to require authentication.
				$name = 'require-authentication|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( '%s', 'rest-api-toolbox' ), $endpoint),
					array( __CLASS__, 'settings_checkbox' ),
					$key,
					$section_auth,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);
			}
		}

		/**
		 * Performs any necessary sanitation on custom post type settings.
		 *
		 * @param  array $settings Custom post type settings
		 * @return array
		 */
		static public function sanitize_cpt_settings( $settings ) {
			return $settings;
		}

		/**
		 * Outputs the No Custom Post Types header.
		 *
		 * @return void
		 */
		static public function section_header_no_cpts() {
			self::header( __( 'No Custom Post Types', 'rest-api-toolbox' ) );
		}
	}
}

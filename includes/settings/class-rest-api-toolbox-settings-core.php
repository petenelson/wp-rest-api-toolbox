<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Core' ) ) {

	class REST_API_Toolbox_Settings_Core extends REST_API_Toolbox_Settings_Base {

		static $settings_key  = 'rest-api-toolbox-settings-core';

		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_core_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( __CLASS__, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ self::$settings_key ] = __( 'Core', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_core_settings() {
			$key = self::$settings_key;

			register_setting( $key, $key, array( __CLASS__, 'sanitize_core_settings') );

			$section = 'core';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'remove-all-core-routes', __( 'Remove All WordPress Core Endpoints', 'rest-api-toolbox' ), array( __CLASS__, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'remove-all-core-routes', 'after' => '' ) );

			$namespace = REST_API_Toolbox_Common::core_namespace();
			$endpoints = REST_API_Toolbox_Common::core_endpoints();

			foreach( $endpoints as $endpoint ) {

				// Add yes/no options to remove the endpoint.
				$name = 'remove-endpoint|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( 'Remove Endpoint: %s', 'rest-api-toolbox' ), $endpoint),
					array( __CLASS__, 'settings_yes_no' ),
					$key,
					$section,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);

				// Add yes/no options to require authentication.
				$name = 'require-authentication|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( 'Require Authentication: %s', 'rest-api-toolbox' ), $endpoint),
					array( __CLASS__, 'settings_yes_no' ),
					$key,
					$section,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);
			}

		}

		static public function sanitize_core_settings( $settings ) {

			return $settings;
		}


	}

}

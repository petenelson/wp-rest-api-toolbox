<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Core' ) ) {

	class REST_API_Toolbox_Settings_Core extends REST_API_Toolbox_Settings_Base {

		private $settings_key  = 'rest-api-toolbox-settings-core';

		public function plugins_loaded() {
			add_action( 'admin_init', array( $this, 'register_core_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( $this, 'add_tab') );
		}

		public function add_tab( $tabs ) {
			$tabs[ $this->settings_key ] = __( 'Core', 'rest-api-toolbox' );
			return $tabs;
		}

		public function register_core_settings() {
			$common = new REST_API_Toolbox_Common();
			$key = $this->settings_key;

			register_setting( $key, $key, array( $this, 'sanitize_core_settings') );

			$section = 'core';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'remove-all-core-routes', __( 'Remove All WordPress Core Endpoints', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'remove-all-core-routes', 'after' => '' ) );

			$namespace = $common->core_namespace();
			$endpoints = $common->core_endpoints();

			foreach( $endpoints as $endpoint ) {
				$name = 'remove-endpoint|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( 'Remove Endpoint: %s', 'rest-api-toolbox' ), $endpoint),
					array( $this, 'settings_yes_no' ),
					$key,
					$section,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);
			}

		}

		public function sanitize_core_settings( $settings ) {

			return $settings;
		}


	}

}

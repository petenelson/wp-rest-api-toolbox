<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_General' ) ) {

	class REST_API_Toolbox_Settings_General extends REST_API_Toolbox_Settings_Base {

		private $settings_key  = 'rest-api-toolbox-settings-general';

		public function plugins_loaded() {
			add_action( 'admin_init', array( $this, 'register_general_settings' ) );
		}


		public function register_general_settings() {
			$key = $this->settings_key;
			$this->plugin_settings_tabs[ $key ] = __( 'General', 'rest-api-toolbox' );

			register_setting( $key, $key, array( $this, 'sanitize_general_settings') );

			$section = 'general';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'disable-rest-api', __( 'Disable REST API', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-rest-api', 'after' => '' ) );

			add_settings_field( 'disable-jsonp', __( 'Disable JSONP Support', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-jsonp', 'after' => '' ) );

			add_settings_field( 'rest-api-prefix', __( 'REST API Prefix', 'rest-api-toolbox' ), array( $this, 'settings_input' ), $key, $section,
				array(
					'key' => $key,
					'name' =>
					'rest-api-prefix',
					'after' => __( 'Custom prefix, default is wp-json', 'rest-api-toolbox' ),
					)
				);

		}

		public function sanitize_general_settings( $settings ) {

			if ( ! empty( $settings['rest-api-prefix'] ) ) {
				$settings['rest-api-prefix'] = sanitize_title( trim( $settings['rest-api-prefix'] ) );
			}

			return $settings;
		}


	}

}


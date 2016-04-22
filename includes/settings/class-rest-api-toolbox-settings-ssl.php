<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_SSL' ) ) {

	class REST_API_Toolbox_Settings_SSL extends REST_API_Toolbox_Settings_Base {

		static $settings_key  = 'rest-api-toolbox-settings-ssl';

		static public function plugins_loaded() {
			add_action( 'admin_init', array( $this, 'register_ssl_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( $this, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ $this->settings_key ] = __( 'SSL', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_ssl_settings( $title ) {
			$key = $this->settings_key;

			register_setting( $key, $key, array( $this, 'sanitize_ssl_settings') );

			$section = 'ssl';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'require-ssl', __( 'Require SSL', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'require-ssl', 'after' => '' ) );

		}


		static public function sanitize_ssl_settings( $settings ) {

			return $settings;
		}

	}

}

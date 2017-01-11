<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_SSL' ) ) {

	class REST_API_Toolbox_Settings_SSL extends REST_API_Toolbox_Settings_Base {

		static $settings_key  = 'rest-api-toolbox-settings-ssl';

		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_ssl_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( __CLASS__, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ self::$settings_key ] = __( 'SSL', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_ssl_settings( $title ) {
			$key = self::$settings_key;

			register_setting( $key, $key, array( __CLASS__, 'sanitize_ssl_settings') );

			$section = 'ssl';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'require-ssl', __( 'Require SSL', 'rest-api-toolbox' ), array( __CLASS__, 'settings_checkbox' ), $key, $section,
				array( 'key' => $key, 'name' => 'require-ssl', 'after' => '' ) );

		}


		static public function sanitize_ssl_settings( $settings ) {

			return $settings;
		}

	}

}

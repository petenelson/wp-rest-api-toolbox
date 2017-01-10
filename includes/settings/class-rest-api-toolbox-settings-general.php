<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_General' ) ) {

	class REST_API_Toolbox_Settings_General extends REST_API_Toolbox_Settings_Base {

		static $settings_key  = 'rest-api-toolbox-settings-general';

		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_general_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( __CLASS__, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ self::$settings_key ] = __( 'General', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_general_settings() {
			$key = self::$settings_key;

			register_setting( $key, $key, array( __CLASS__, 'sanitize_general_settings') );

			$section = 'general';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'disable-rest-api', __( 'Disable REST API', 'rest-api-toolbox' ), array( __CLASS__, 'settings_checkbox' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-rest-api', 'after' => '' ) );

			add_settings_field( 'disable-jsonp', __( 'Disable JSONP Support', 'rest-api-toolbox' ), array( __CLASS__, 'settings_checkbox' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-jsonp', 'after' => '' ) );

			add_settings_field( 'rest-api-prefix', __( 'REST API Prefix', 'rest-api-toolbox' ), array( __CLASS__, 'settings_input' ), $key, $section,
				array(
					'key' => $key,
					'name' =>
					'rest-api-prefix',
					'after' => __( 'Custom prefix, default is wp-json', 'rest-api-toolbox' ),
					)
				);

		}

		static public function sanitize_general_settings( $settings ) {

			if ( ! empty( $settings['rest-api-prefix'] ) ) {
				$settings['rest-api-prefix'] = sanitize_title( trim( $settings['rest-api-prefix'] ) );
			}

			return $settings;
		}


	}

}


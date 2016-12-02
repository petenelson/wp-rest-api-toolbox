<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Help' ) ) {

	class REST_API_Toolbox_Settings_Help extends REST_API_Toolbox_Settings_Base {

		static public function get_settings_key() {
			return 'rest-api-toolbox-settings-help';
		}

		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_help_settings' ) );
			add_filter( 'rest-api-toolbox-settings-tabs', array( __CLASS__, 'add_tab') );
		}

		static public function add_tab( $tabs ) {
			$tabs[ self::get_settings_key() ] = __( 'Help', 'rest-api-toolbox' );
			return $tabs;
		}

		static public function register_help_settings( $title ) {
			add_settings_section( 'help', '', array( __CLASS__, 'section_header' ), self::get_settings_key() );
		}

		static public function section_header( $args ) {
			include_once REST_API_TOOLBOX_ROOT . 'admin/partials/admin-help.php';
		}

	}

}

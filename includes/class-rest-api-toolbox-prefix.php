<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Prefix' ) ) {

	class REST_API_Toolbox_Prefix extends REST_API_Toolbox_Base {

		static public function plugins_loaded() {

			add_filter( 'rest_url_prefix', array( __CLASS__, 'change_url_prefix' ), 100 );

		}

		static public function change_url_prefix( $prefix ) {

			$custom_prefix   = REST_API_Toolbox_Settings::setting_get( 'general', 'rest-api-prefix' );
			$prefix          = ! empty( $custom_prefix ) ? $custom_prefix : $prefix;

			return $prefix;
		}

	}

}

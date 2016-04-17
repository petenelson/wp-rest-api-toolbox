<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Prefix' ) ) {

	class REST_API_Toolbox_Prefix extends REST_API_Toolbox_Base {

		public function plugins_loaded() {

			add_filter( 'rest_url_prefix', array( $this, 'change_url_prefix' ), 100 );

		}

		public function change_url_prefix( $prefix ) {

			$custom_prefix   = $this->settings->setting_get( 'general', 'rest-api-prefix' );
			$prefix          = ! empty( $custom_prefix ) ? $custom_prefix : $prefix;

			return $prefix;
		}

	}

}

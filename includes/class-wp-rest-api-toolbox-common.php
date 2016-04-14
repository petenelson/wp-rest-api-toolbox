<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WP_REST_API_Toolbox_Common' ) ) {

	class WP_REST_API_Toolbox_Common {

		public function plugins_loaded() {
			
			add_filter( 'rest_enabled', array( $this, 'rest_api_disabled_filter' ) );

		}

		public function rest_api_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$disabled = apply_filters( 'wp-rest-api-toolbox-setting-is-enabled', false, 'wp-rest-api-toolbox-settings-general', 'disable-rest-api' );
				if ( $disabled ) {
					$enabled  = false;
				}
			}
			return $enabled;
		}


	}

}

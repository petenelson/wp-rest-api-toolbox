<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WP_REST_API_Toolbox_Common' ) ) {

	class WP_REST_API_Toolbox_Common {

		public function plugins_loaded() {
			
			add_filter( 'rest_enabled', array( $this, 'rest_api_disabled_filter' ) );

		}

		public function rest_api_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$settings = new WP_REST_API_Toolbox_Settings();
				$disable_rest_api = $settings->setting_is_enabled( 'general', 'disable-rest-api' );

				if ( $disable_rest_api ) {
					$enabled = false;
				}

			}
			return $enabled;
		}


	}

}

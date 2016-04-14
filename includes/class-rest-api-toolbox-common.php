<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Common' ) ) {

	class REST_API_Toolbox_Common {

		public function plugins_loaded() {
			
			add_filter( 'rest_enabled', array( $this, 'rest_api_disabled_filter' ) );

			add_filter( 'rest_pre_dispatch', array( $this, 'disallow_non_ssl' ), 10, 3 );

		}

		public function rest_api_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$settings = new REST_API_Toolbox_Settings();
				$disable_rest_api = $settings->setting_is_enabled( 'general', 'disable-rest-api' );

				if ( $disable_rest_api ) {
					$enabled = false;
				}

			}
			return $enabled;
		}


		public function disallow_non_ssl( $response, $server, $request ) {
			if ( ! is_ssl() ) {

				$settings = new REST_API_Toolbox_Settings();
				$require_ssl = $settings->setting_is_enabled( 'ssl', 'require-ssl' );

				if ( $require_ssl ) {
					$response = new WP_Error( 'rest_forbidden', __( "SSL is required to access the REST API" ), array( 'status' => 403 ) );
				}

			}
			return $response;
		}


	}

}

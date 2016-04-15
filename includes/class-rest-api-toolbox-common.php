<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Common' ) ) {

	class REST_API_Toolbox_Common {

		public function plugins_loaded() {
			
			add_filter( 'rest_enabled',         array( $this, 'rest_api_disabled_filter' ), 100 );

			add_filter( 'rest_pre_dispatch',    array( $this, 'disallow_non_ssl' ), 100, 3 );


			add_filter( 'rest_index',           array( $this, 'remove_wordpress_core_namespace' ), 100, 3 );
			add_filter( 'rest_endpoints',       array( $this, 'remove_wordpress_core_endpoints'), 100, 1 );

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


		public function remove_wordpress_core_namespace( $response ) {

			$settings = new REST_API_Toolbox_Settings();
			$remove_all_core_endpoints = $settings->setting_is_enabled( 'core', 'remove-all-core-routes' );
			if ( $remove_all_core_endpoints ) {
				if ( ! empty( $response->data ) && ! empty( $response->data['namespaces'] ) ) {
					for( $i = count( $response->data['namespaces'] ) - 1; $i >= 0; $i-- ) {
						if ( 'wp/v2' === $response->data['namespaces'][ $i ] ) {
							unset( $response->data['namespaces'][ $i ] );
							$response->data['namespaces'] = array_values( $response->data['namespaces'] );
							break;
						}
					}
				}
			}
			return $response;
		}


		public function remove_wordpress_core_endpoints( $endpoints ) {

			$settings = new REST_API_Toolbox_Settings();
			$remove_all_core_endpoints = $settings->setting_is_enabled( 'core', 'remove-all-core-routes' );

			if ( $remove_all_core_endpoints ) {
				foreach ( array_keys( $endpoints ) as $endpoint ) {
					if ( 0 === stripos( $endpoint, '/wp/v2' ) ) {
						unset( $endpoints[ $endpoint ] );
					}
				}
			}

			return $endpoints;
		}

	}

}

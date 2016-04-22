<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Common' ) ) {

	class REST_API_Toolbox_Common {

		static public function plugins_loaded() {
			
			add_filter( 'rest_enabled',         array( $this, 'rest_api_disabled_filter' ), 100 );
			add_filter( 'rest_jsonp_enabled',   array( $this, 'rest_jsonp_disabled_filter' ), 100 );

			add_filter( 'rest_pre_dispatch',    array( $this, 'disallow_non_ssl' ), 100, 3 );


			add_filter( 'rest_index',           array( $this, 'remove_wordpress_core_namespace' ), 100, 3 );
			add_filter( 'rest_endpoints',       array( $this, 'remove_all_core_endpoints'), 100, 1 );

			add_filter( 'rest_endpoints',       array( $this, 'remove_selected_core_endpoints'), 100, 1 );

		}


		static public function endpoint_exists( $endpoint ) {

			rest_api_loaded();

			$wp_rest_server = $this->get_rest_api_server();
			$routes = $wp_rest_server->get_routes();

			$endpoint_exists = false;
			foreach ( array_keys( $routes ) as $route_endpoint ) {
				if ( 0 === stripos( $route_endpoint, $endpoint ) ) {
					$endpoint_exists = true;
					break;
				}
			}

			return $endpoint_exists;

		}

		static public function get_rest_api_server() {

			global $wp_rest_server;

			if ( is_null( $wp_rest_server ) ) {
				$wp_rest_server_class = apply_filters( 'wp_rest_server_class', 'WP_REST_Server' );
				$wp_rest_server = new $wp_rest_server_class;
				do_action( 'rest_api_init', $wp_rest_server );
			}

			return $wp_rest_server;

		}


		static public function core_namespace() {
			return apply_filters( 'rest-api-toolbox-core-namespace', 'wp/v2' );
		}


		static public function core_endpoints() {
			$endpoints = array(
				'posts',
				'pages',
				'users',
				'media',
				'categories',
				'tags',
				'comments',
				'taxonomies',
				'types',
				'statuses',
			);
			return apply_filters( 'rest-api-toolbox-core-endpoints', $endpoints );
		}

		static public function rest_api_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$settings = new REST_API_Toolbox_Settings();
				$disable_rest_api = $settings->setting_is_enabled( 'general', 'disable-rest-api' );

				if ( $disable_rest_api ) {
					$enabled = false;
				}

			}
			return $enabled;
		}


		static public function rest_jsonp_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$settings = new REST_API_Toolbox_Settings();
				$disable_jsonp = $settings->setting_is_enabled( 'general', 'disable-jsonp' );

				if ( $disable_jsonp ) {
					$enabled = false;
				}

			}
			return $enabled;
		}


		static public function disallow_non_ssl( $response, $server, $request ) {
			if ( ! is_ssl() ) {

				$settings = new REST_API_Toolbox_Settings();
				$require_ssl = $settings->setting_is_enabled( 'ssl', 'require-ssl' );

				if ( $require_ssl ) {
					$response = new WP_Error( 'rest_forbidden', __( "SSL is required to access the REST API" ), array( 'status' => 403 ) );
				}

			}
			return $response;
		}


		static public function remove_wordpress_core_namespace( $response ) {

			$settings = new REST_API_Toolbox_Settings();
			$remove_all = $settings->setting_is_enabled( 'core', 'remove-all-core-routes' );
			if ( $remove_all ) {
				if ( ! empty( $response->data ) && ! empty( $response->data['namespaces'] ) ) {
					for( $i = count( $response->data['namespaces'] ) - 1; $i >= 0; $i-- ) {
						if ( $this->core_namespace() === $response->data['namespaces'][ $i ] ) {
							unset( $response->data['namespaces'][ $i ] );
							$response->data['namespaces'] = array_values( $response->data['namespaces'] );
							break;
						}
					}
				}
			}
			return $response;
		}


		static public function remove_all_core_endpoints( $routes ) {

			$settings = new REST_API_Toolbox_Settings();
			$remove_all = $settings->setting_is_enabled( 'core', 'remove-all-core-routes' );

			if ( $remove_all ) {
				$routes = $this->remove_endpoint( $routes, '/' . $this->core_namespace() );
			}

			return $routes;
		}


		static public function remove_selected_core_endpoints( $routes ) {

			$settings = new REST_API_Toolbox_Settings();
			$core_settings = get_option( $settings->options_key( 'core' ) );
			$core_settings = ! is_array( $core_settings ) ? array() : $core_settings;

			$pattern = "/remove-endpoint\\|(.+)/";
			$endpoints = array();

			foreach ( $core_settings as $setting => $enabled ) {
				if ( '1' === $enabled ) {
					$matches = array();
					if ( 1 === preg_match( $pattern, $setting, $matches ) ) {
						if ( ! empty( $matches ) && count( $matches ) > 0 && ! empty( $matches[1] ) ) {
							$endpoints[] = $matches[1];
						}
					}
				}
			}

			$endpoints = apply_filters( 'rest-api-toolbox-remove-endpoints', $endpoints );

			foreach ( $endpoints as $endpoint ) {
				$routes = $this->remove_endpoint( $routes, $endpoint );
			}

			return $routes;

		}


		static public function remove_endpoint( $routes, $remove_endpoint ) {
			foreach ( array_keys( $routes ) as $endpoint ) {
				if ( 0 === strpos( $endpoint, $remove_endpoint ) ) {
					unset( $routes[ $endpoint ] );
				}
			}
			return $routes;
		}


	}

}

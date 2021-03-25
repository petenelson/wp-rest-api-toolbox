<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Common' ) ) {

	class REST_API_Toolbox_Common {

		static public function plugins_loaded() {

			// Setup disabled filter based on the changing in WP 4.7
			if ( REST_API_Toolbox_Common::wp_version_at_least( '4.7' ) ) {
				add_filter( 'rest_authentication_errors',   array( __CLASS__, 'rest_authentication_errors_filter' ), 100 );
			} else {
				add_filter( 'rest_enabled',                 array( __CLASS__, 'rest_enabled_filter' ), 100 );
			}

			// Filter hook to disable JSONP.
			add_filter( 'rest_jsonp_enabled',           array( __CLASS__, 'rest_jsonp_disabled_filter' ), 100 );

			// Filter hook to force SSL.
			add_filter( 'rest_pre_dispatch',            array( __CLASS__, 'disallow_non_ssl_filter' ), 100, 3 );

			// Filter hooks to remove endpoints.
			add_filter( 'rest_index',                   array( __CLASS__, 'remove_wordpress_core_namespace_filter' ), 100, 3 );
			add_filter( 'rest_endpoints',               array( __CLASS__, 'remove_all_core_endpoints_filter'), 100, 1 );
			add_filter( 'rest_endpoints',               array( __CLASS__, 'remove_selected_endpoints_filter'), 100, 1 );

			// Filter hook to require authentication for specific endpoints.
			add_filter( 'rest_pre_dispatch',            array( __CLASS__, 'endpoint_requires_authentication_filter' ), 100, 3 );
		}

		/**
		 * Returns true if the WordPress version is at least the supplied
		 * version.
		 *
		 * @param string $version The version number to compare.
		 * @return bool
		 */
		static public function wp_version_at_least( $version ) {
			return version_compare( get_bloginfo('version' ), $version )  >= 0;
		}

		static public function endpoint_exists( $endpoint ) {

			rest_api_loaded();

			$wp_rest_server = self::get_rest_api_server();
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

			// Add the settings endpoint introduced in 4.7
			if ( REST_API_Toolbox_Common::wp_version_at_least( '4.7' ) ) {
				$endpoints[] = 'settings';
			}

			return apply_filters( 'rest-api-toolbox-core-endpoints', $endpoints );
		}

		/**
		 * Filter hook for disabling the REST API in WordPress 4.7 and higher.
		 *
		 * @param  mixed $error WP_Error if authentication error, null if authentication
		 *                      method wasn't used, true if authentication succeeded.
		 * @return mixed
		 */
		static public function rest_authentication_errors_filter( $error ) {
			if ( ! is_wp_error( $error ) ) {
				$disable_rest_api = REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' );

				if ( $disable_rest_api ) {
					$error = new WP_Error( 'rest_disabled', __( 'The REST API is disabled on this site.' ), array( 'status' => 404 ) );
				}
			}

			return $error;
		}

		/**
		 * Filter hook for disabling the REST API in WordPress 4.6 and earlier.
		 *
		 * @param  bool $enabled Default value.
		 * @return bool          False if disabled, otherwise the default value.
		 */
		static public function rest_enabled_filter( $enabled ) {
			if ( $enabled ) {
				$disable_rest_api = REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' );

				if ( $disable_rest_api ) {
					$enabled = false;
				}

			}

			return $enabled;
		}

		static public function rest_jsonp_disabled_filter( $enabled ) {
			if ( $enabled ) {
				$disable_jsonp = REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-jsonp' );

				if ( $disable_jsonp ) {
					$enabled = false;
				}

			}
			return $enabled;
		}


		static public function disallow_non_ssl_filter( $response, $server, $request ) {

			// Don't check for SSL during WP-CLI commands.
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				return $response;
			}

			if ( ! is_ssl() ) {

				$require_ssl = REST_API_Toolbox_Settings::setting_is_enabled( 'ssl', 'require-ssl' );

				if ( $require_ssl ) {
					$response = new WP_Error( 'rest_forbidden', __( "SSL is required to access the REST API" ), array( 'status' => 403 ) );
				}

			}
			return $response;
		}


		static public function remove_wordpress_core_namespace_filter( $response ) {

			$remove_all = REST_API_Toolbox_Settings::setting_is_enabled( 'core', 'remove-all-core-routes' );
			if ( $remove_all ) {
				if ( ! empty( $response->data ) && ! empty( $response->data['namespaces'] ) ) {
					for( $i = count( $response->data['namespaces'] ) - 1; $i >= 0; $i-- ) {
						if ( self::core_namespace() === $response->data['namespaces'][ $i ] ) {
							unset( $response->data['namespaces'][ $i ] );
							$response->data['namespaces'] = array_values( $response->data['namespaces'] );
							break;
						}
					}
				}
			}
			return $response;
		}


		static public function remove_all_core_endpoints_filter( $routes ) {

			$remove_all = REST_API_Toolbox_Settings::setting_is_enabled( 'core', 'remove-all-core-routes' );

			if ( $remove_all ) {
				$routes = self::remove_endpoint( $routes, '/' . self::core_namespace() );
			}

			return $routes;
		}


		static public function remove_selected_endpoints_filter( $routes ) {

			// Get the list of core endpoints.
			$core_settings = get_option( REST_API_Toolbox_Settings::options_key( 'core' ) );
			$core_settings = ! is_array( $core_settings ) ? array() : $core_settings;

			// Get the list of custom post types.
			$cpt_settings  = get_option( REST_API_Toolbox_Settings::options_key( 'cpt' ) );
			$cpt_settings  = ! is_array( $cpt_settings ) ? array() : $cpt_settings;

			// Combine the list.
			$settings = array_merge( $core_settings, $cpt_settings );

			$pattern = "/remove-endpoint\\|(.+)/";
			$endpoints = array();

			foreach ( $settings as $setting => $enabled ) {
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
				$routes = self::remove_endpoint( $routes, $endpoint );
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

		/**
		 * Filter hook to require authentication on specific endpoints.
		 *
		 * @param mixed           $result      Response to replace the requested version with. Can be anything
		 *                                     a normal endpoint can return, or null to not hijack the request.
		 * @param WP_REST_Server  $rest_server Server instance.
		 * @param WP_REST_Request $request     Request used to generate the response.
		 * @return mixed
		 */
		static public function endpoint_requires_authentication_filter( $result, $rest_server, $request ) {

			// Get the route for the request.
			$route = $request->get_route();

			// Get the list of core endpoints.
			$core_settings = get_option( REST_API_Toolbox_Settings::options_key( 'core' ) );
			$core_settings = ! is_array( $core_settings ) ? array() : $core_settings;

			// Get the list of custom post types.
			$cpt_settings  = get_option( REST_API_Toolbox_Settings::options_key( 'cpt' ) );
			$cpt_settings  = ! is_array( $cpt_settings ) ? array() : $cpt_settings;

			// Combine the list.
			$settings = array_merge( $core_settings, $cpt_settings );

			// See if this route is configured to require authentication and
			// if there is a current user logged in.
			if ( ! empty( $settings ) && is_array( $settings ) && ! is_user_logged_in() ) {

				$require_auth = false;
				$require_auth_start = 'require-authentication|';

				// Loop through each setting and see if the route matches.
				foreach ( $settings as $key => $enabled ) {
					if ( '1' === $enabled && 0 === stripos( $key, $require_auth_start ) ) {

						// Strip off the start to find the route.
						$key = str_replace( $require_auth_start, '', $key );

						// See if we have an exact match (ex: /wp/v2/users)
						if ( $route === $key ) {
							$require_auth = true;
						} else {

							// Check it against a regex for things like
							// /wp/v2/users/1
							$regex = '^' . str_replace( '/', '\/', $key ) . '\/.+$';

							if ( 1 === preg_match( '/' . $regex . '/', $route ) ) {
								$require_auth = true;
							}
						}
					}

					// Return a WP_Error is authentication is required but there
					// is no current user logged in.
					if ( $require_auth ) {
						return new WP_Error(
							'rest_cannot_view',
							sprintf( __( 'The REST API route %s requires authentication on this site.', 'rest-api-toolbox' ), $route ),
							array( 'status' => 401 )
						);
					}
				}
			}

			return $result;
		}


		/**
		 * Returns a list of custom post types that are exposed via the
		 * REST API.
		 *
		 * @return array
		 */
		static public function get_custom_post_types() {

			// Build the filters for the list of post types.
			$args = array(
				'show_in_rest' => true,
				'_builtin' => false,
				);

			// Allow the return value to be filterable.
			$post_types = apply_filters( 'rest-api-toolbox-custom-post-types', get_post_types( $args, 'objects' ) );

			return $post_types;
		}

	}

}

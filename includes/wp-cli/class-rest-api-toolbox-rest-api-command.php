<?php
/**
 * Manage general REST API settings
 */
class REST_API_Toolbox_REST_API_Command extends REST_API_Toolbox_Base_Command  {

	/**
	 * Disables the REST API
	 *
	 * ## OPTIONS
	 *
	 * <core_endpoint>
	 * Core endpoint to disable (posts, media, users, etc)
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox disable
	 *
	 */
	function disable( $positional_args, $assoc_args = array() ) {
		if ( ! empty( $positional_args ) ) {
			$core_endpoint = $positional_args[0];

			if ( ! in_array( $core_endpoint, REST_API_Toolbox_Common::core_endpoints() ) ) {
				WP_CLI::Error( sprintf( "Invalid core endpoint: %s", $core_endpoint ) );
				exit;
			}

			$name = 'remove-endpoint|/' . REST_API_Toolbox_Common::core_namespace() . '/' . $core_endpoint;
			$this->change_enabled_setting( 'core', $name, true );

			WP_CLI::Success( sprintf( "Core endpoint %s disabled" , $core_endpoint ) );

		} else {
			$this->change_rest_api_enabled( true );
			WP_CLI::Success( 'REST API disabled (other plugins can override this)' );
		}
	}


	/**
	 * Enables the REST API
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox enable
	 *
	 */
	function enable( $positional_args, $assoc_args = array() ) {
		if ( ! empty( $positional_args ) ) {
			$core_endpoint = $positional_args[0];

			if ( ! in_array( $core_endpoint, REST_API_Toolbox_Common::core_endpoints() ) ) {
				WP_CLI::Error( sprintf( "Invalid core endpoint: %s", $core_endpoint ) );
				exit;
			}

			$name = 'remove-endpoint|/' . REST_API_Toolbox_Common::core_namespace() . '/' . $core_endpoint;
			$this->change_enabled_setting( 'core', $name, false );

			WP_CLI::Success( sprintf( "Core endpoint %s enabled", $core_endpoint ) );

		} else {
			$this->change_rest_api_enabled( false );
			WP_CLI::Success( 'REST API enabled (other plugins can override this)' );
		}
	}


	/**
	 * Gets the enabled/disabled status the REST API
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox status
	 *
	 */
	function status( $positional_args, $assoc_args = array() ) {

		if ( ! empty( $positional_args ) ) {
			$core_endpoint = $positional_args[0];

			if ( ! in_array( $core_endpoint, REST_API_Toolbox_Common::core_endpoints() ) ) {
				WP_CLI::Error( sprintf( "Invalid core endpoint: %s", $core_endpoint ) );
				exit;
			}

			$exists = REST_API_Toolbox_Common::endpoint_exists( '/' . REST_API_Toolbox_Common::core_namespace() . '/' . $core_endpoint );

			if ( $exists ) {
				WP_CLI::Line( sprintf( "Core endpoint %s is enabled.", $core_endpoint ) );
			} else {
				WP_CLI::Line( sprintf( "Core endpoint %s is disabled.", $core_endpoint ) );
			}


		} else {

			if ( REST_API_Toolbox_Common::wp_version_at_least( '4.7' ) ) {
				// If we get a WP_Error back, the API is disabled.
				$enabled = ! is_wp_error( apply_filters( 'rest_authentication_errors', null ) );
			} else {
				$enabled = apply_filters( 'rest_enabled', true );
			}

			if ( $enabled ) {
				WP_CLI::Line( "The REST API is enabled." );
			} else {
				WP_CLI::Line( "The REST API is disabled." );
			}

		}

	}

	private function change_rest_api_enabled( $enabled ) {
		$this->change_enabled_setting( 'general', 'disable-rest-api', $enabled );
	}

}

WP_CLI::add_command( 'rest-api-toolbox',          'REST_API_Toolbox_REST_API_Command' );

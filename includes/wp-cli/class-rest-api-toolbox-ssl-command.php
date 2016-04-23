<?php
/**
 * Manage REST API SSL settings
 */
class REST_API_Toolbox_SSL_Command extends REST_API_Toolbox_Base_Command  {

	/**
	 * Make SSL required for REST API endpoints
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox ssl required
	 *
	 */
	function required( $positional_args, $assoc_args = array() ) {
		$this->change_enabled_setting( 'ssl', 'require-ssl', true );
		WP_CLI::Success( 'SSL is now required for REST API endpoints' );
	}

	/**
	 * Make SSL optional for REST API endpoints
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox ssl optional
	 *
	 */
	function optional( $positional_args, $assoc_args = array() ) {
		$this->change_enabled_setting( 'ssl', 'require-ssl', false );
		WP_CLI::Success( 'SSL is now optional for REST API endpoints' );
	}


	/**
	 * Gets the SSL status the REST API
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox ssl status
	 *
	 */
	function status( $positional_args, $assoc_args = array() ) {

		$require_ssl = REST_API_Toolbox_Settings::setting_is_enabled( 'ssl', 'require-ssl' );

		if ( $require_ssl ) {
			WP_CLI::Line( "SSL is required for REST API endpoints." );
		} else {
			WP_CLI::Line( "SSL is optional for REST API endpoints." );
		}

	}


}

WP_CLI::add_command( 'rest-api-toolbox ssl',          'REST_API_Toolbox_SSL_Command' );

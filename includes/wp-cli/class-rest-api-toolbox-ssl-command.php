<?php
/**
 * Manage REST API SSL settings
 */
class REST_API_Toolbox_SSL_Command extends WP_CLI_Command  {

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
		$this->change_enabled( 'require-ssl', true );
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
		$this->change_enabled( 'require-ssl', false );
		WP_CLI::Success( 'SSL is now optional for REST API endpoints' );
	}

	private function change_enabled( $setting, $enabled ) {
		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'ssl', $setting, $enabled );
	}

}

WP_CLI::add_command( 'rest-api-toolbox ssl',          'REST_API_Toolbox_SSL_Command' );

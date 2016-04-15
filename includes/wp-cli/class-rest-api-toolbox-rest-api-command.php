<?php
/**
 * Manage general REST API settings
 */
class REST_API_Toolbox_REST_API_Command extends WP_CLI_Command  {

	/**
	 * Disables the REST API
	 *
	 * ## OPTIONS
	 *
	 * ## EXAMPLES
	 *
	 *     wp rest-api-toolbox disable
	 *
	 */
	function disable( $positional_args, $assoc_args = array() ) {
		$this->change_enabled( true );
		WP_CLI::Success( 'REST API disabled (other plugins can override this)' );
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
		$this->change_enabled( false );
		WP_CLI::Success( 'REST API enabled (other plugins can override this)' );
	}

	private function change_enabled( $enabled ) {
		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'general', 'disable-rest-api', $enabled );
	}

}

WP_CLI::add_command( 'rest-api-toolbox',          'REST_API_Toolbox_REST_API_Command' );

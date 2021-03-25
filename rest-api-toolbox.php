<?php
/**
 * Plugin Name: REST API Toolbox
 * Version: 1.4.3
 * Description: Allows easy tweaks of several REST API settings
 * Author: Pete Nelson
 * Author URI: https://github.com/petenelson/wp-rest-api-toolbox
 * Plugin URI: https://wordpress.org/plugins/rest-api-toolbox
 * Text Domain: rest-api-toolbox
 * Domain Path: /languages
 * @package wp-rest-api-toolbox
 */

class REST_API_Toolbox_Plugin {


	static function define_constants() {
		if ( ! defined( 'REST_API_TOOLBOX_ROOT' ) ) {
			define( 'REST_API_TOOLBOX_ROOT', trailingslashit( dirname( __FILE__ ) ) );
		}

		if ( ! defined( 'REST_API_TOOLBOX_FILE' ) ) {
			define( 'REST_API_TOOLBOX_FILE', __FILE__ );
		}

		if ( ! defined( 'REST_API_TOOLBOX_BASENAME' ) ) {
			define( 'REST_API_TOOLBOX_BASENAME', plugin_basename( REST_API_TOOLBOX_FILE ) );
		}
	}

	static function get_required_files() {
		$include_files = array(
			'class-rest-api-toolbox-base',
			'class-rest-api-toolbox-common',
			'class-rest-api-toolbox-prefix',
			'class-rest-api-toolbox-i18n',
			'class-rest-api-toolbox-admin',
			'settings/class-rest-api-toolbox-settings-base',
			'settings/class-rest-api-toolbox-settings',
			'settings/class-rest-api-toolbox-settings-general',
			'settings/class-rest-api-toolbox-settings-core',
			'settings/class-rest-api-toolbox-settings-custom-post-types',
			'settings/class-rest-api-toolbox-settings-ssl',
			'settings/class-rest-api-toolbox-settings-help',
			);

		$files = array();
		foreach ( $include_files as $include_file ) {
			$files[] = REST_API_TOOLBOX_ROOT . 'includes/' . $include_file . '.php';
		}
		return $files;
	}

	static function get_class_names() {
		return array(
			'REST_API_Toolbox_Base',
			'REST_API_Toolbox_Common',
			'REST_API_Toolbox_Prefix',
			'REST_API_Toolbox_i18n',
			'REST_API_Toolbox_Admin',
			'REST_API_Toolbox_Settings_Base',
			'REST_API_Toolbox_Settings',
			'REST_API_Toolbox_Settings_General',
			'REST_API_Toolbox_Settings_Core',
			'REST_API_Toolbox_Settings_Custom_Post_Types',
			'REST_API_Toolbox_Settings_SSL',
			'REST_API_Toolbox_Settings_Help',
			);
	}

	static function require_files( $files ) {
		foreach( $files as $file ) {
			require_once $file;
		}
	}

}


REST_API_Toolbox_Plugin::define_constants();
REST_API_Toolbox_Plugin::require_files( REST_API_Toolbox_Plugin::get_required_files() );

// load plugin classes
foreach( REST_API_Toolbox_Plugin::get_class_names() as $class_name ) {
	$classes = array();
	if ( class_exists( $class_name ) ) {
		$classes[] = new $class_name;
	}

	foreach ( $classes as $class ) {
		if ( method_exists( $class, 'plugins_loaded' ) ) {
			add_action( 'plugins_loaded', array( $class, 'plugins_loaded' ) );
		}
		if ( method_exists( $class, 'activation_hook' ) ) {
			register_activation_hook( __FILE__, array( $class, 'activation_hook' ) );
		}
	}
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once REST_API_TOOLBOX_ROOT . 'includes/wp-cli/setup.php';
}
 
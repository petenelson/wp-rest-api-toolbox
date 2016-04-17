<?php
/**
 * Plugin Name: REST API Toolbox
 * Version: 1.0.0
 * Description: Allows easy tweaks of several REST API settings
 * Author: Pete Nelson
 * Author URI: https://github.com/petenelson/wp-rest-api-toolbox
 * Plugin URI: https://wordpress.org/plugins/rest-api-toolbox
 * Text Domain: rest-api-toolbox
 * Domain Path: /languages
 * @package wp-rest-api-toolbox
 */

class REST_API_Toolbox_Plugin {


	function define_constants() {
		if ( ! defined( 'REST_API_TOOLBOX_ROOT' ) ) {
			define( 'REST_API_TOOLBOX_ROOT', trailingslashit( dirname( __FILE__ ) ) );
		}
	}

	function get_required_files() {
		$include_files = array(
			'base',
			'common',
			'prefix',
			'i18n',
			'settings'
			);

		$files = array();
		foreach ( $include_files as $include_file ) {
			$files[] = REST_API_TOOLBOX_ROOT . 'includes/class-rest-api-toolbox-' . $include_file . '.php';
		}
		return $files;
	}

	function get_class_names() {
		return array(
			'REST_API_Toolbox_Base',
			'REST_API_Toolbox_Common',
			'REST_API_Toolbox_Prefix',
			'REST_API_Toolbox_i18n',
			'REST_API_Toolbox_Settings',
			);
	}

	function require_files( $files ) {
		foreach( $files as $file ) {
			require_once $file;
		}
	}

}

$plugin = new REST_API_Toolbox_Plugin();
$plugin->define_constants();
$plugin->require_files( $plugin->get_required_files() );

// load plugin classes
foreach( $plugin->get_class_names() as $class_name ) {
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
 
<?php
/**
 * Plugin Name: REST API Toolbox
 * Version: 1.0.0
 * Description: Allows easy tweaks of several REST API settings
 * Author: Pete Nelson
 * Author URI: https://github.com/petenelson/wp-rest-api-toolbox
 * Plugin URI: https://wordpress.org/plugins/wp-rest-api-toolbox
 * Text Domain: wp-rest-api-toolbox
 * Domain Path: /languages
 * @package wp-rest-api-toolbox
 */

class WP_REST_API_Toolbox_Plugin {


	function define_constants() {
		if ( ! defined( 'WP_REST_API_TOOLBOX_ROOT' ) ) {
			define( 'WP_REST_API_TOOLBOX_ROOT', trailingslashit( dirname( __FILE__ ) ) );
		}
	}

	function get_required_files() {
		$include_files = array( 'common', 'i18n', 'settings' );
		$files = array();
		foreach ( $include_files as $include_file ) {
			$files[] = WP_REST_API_TOOLBOX_ROOT . 'includes/class-wp-rest-api-toolbox-' . $include_file . '.php';
		}
		return $files;
	}

	function get_class_names() {
		return array(
			'WP_REST_API_Toolbox_Common',
			'WP_REST_API_Toolbox_i18n',
			'WP_REST_API_Toolbox_Settings',
			);
	}

	function require_files( $files ) {
		foreach( $files as $file ) {
			require_once $file;
		}
	}

}

$plugin = new WP_REST_API_Toolbox_Plugin();
$plugin->define_constants();
$plugin->require_files( $plugin->get_required_files() );

// load plugin classes
foreach( $plugin->get_class_names() as $class_name ) {
	$classes = array();
	if ( class_exists( $class_name ) ) {
		$classes[] = new $class_name;
	}

	foreach ( $classes as $class ) {
		add_action( 'plugins_loaded', array( $class, 'plugins_loaded' ) );
	}
}

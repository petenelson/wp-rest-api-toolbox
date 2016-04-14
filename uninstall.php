<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die( 'restricted access' );

$keys = array(
	'wp-rest-api-toolbox-general',
	'wp-rest-api-toolbox-core',
	'wp-rest-api-toolbox-ssl',
);

// remove options
foreach ( $keys as $key ) {
	delete_option( $key );
}

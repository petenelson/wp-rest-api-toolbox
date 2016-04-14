<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die( 'restricted access' );

$keys = array(
	'wp-rest-api-toolbox-general',
);

// remove options
foreach ( $keys as $key ) {
	delete_option( $key );
}

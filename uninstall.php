<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die( 'restricted access' );

$keys = array(
	'rest-api-toolbox-general',
	'rest-api-toolbox-core',
	'rest-api-toolbox-ssl',
);

// remove options
foreach ( $keys as $key ) {
	delete_option( $key );
}

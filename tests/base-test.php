<?php

class REST_API_Toolbox_Test_Base extends WP_UnitTestCase {

	function endpoint_exists( $endpoint ) {

		global $wp_rest_server;
		$routes = $wp_rest_server->get_routes();

		$endpoint_exists = false;
		foreach ( array_keys( $routes ) as $route_endpoint ) {
			if ( 0 === stripos( $route_endpoint, $endpoint ) ) {
				$endpoint_exists = true;
				break;
			}
		}

		return $endpoint_exists;

	}

}
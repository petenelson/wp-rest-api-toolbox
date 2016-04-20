<?php

class REST_API_Toolbox_Test_Core extends REST_API_Toolbox_Test_Base {

	function test_remove_core() {

		//create_initial_rest_routes();

		$common = new REST_API_Toolbox_Common();
		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'core', 'remove-all-core-routes', true );

		$this->assertEquals( true, $settings->setting_is_enabled( 'core', 'remove-all-core-routes' ) );

		$wp_rest_server = $common->get_rest_api_server();
		$routes     = $wp_rest_server->get_routes();
		$index      = $wp_rest_server->get_index( array( 'context' => 'view' ) );

		$this->assertNotEmpty( $index );
		$this->assertNotEmpty( $index->data );
		$this->assertNotNull( $index->data['namespaces'] );
		$this->assertNotContains( 'wp/v2', $index->data['namespaces'] );

		// verify the routes
		$this->assertNotEmpty( $routes );

		$has_wp_route = false;
		foreach ( array_keys( $routes ) as $endpoint ) {
			if ( 0 === stripos( $endpoint, '/wp/v2' ) ) {
				$has_wp_route = true;
				break;
			}
		}

		$this->assertFalse( $has_wp_route );

	}

	function test_do_not_remove_core() {

		//create_initial_rest_routes();

		$common = new REST_API_Toolbox_Common();
		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'core', 'remove-all-core-routes', false );

		$this->assertEquals( false, $settings->setting_is_enabled( 'core', 'remove-all-core-routes' ) );

		$wp_rest_server = $common->get_rest_api_server();
		$routes     = $wp_rest_server->get_routes();
		$index      = $wp_rest_server->get_index( array( 'context' => 'view' ) );

		// verify the namespaces
		$this->assertNotEmpty( $index );
		$this->assertNotEmpty( $index->data );
		$this->assertNotNull( $index->data['namespaces'] );
		$this->assertContains( 'wp/v2', $index->data['namespaces'] );

		// verify the routes
		$this->assertNotEmpty( $routes );

		$has_wp_route = false;
		foreach ( array_keys( $routes ) as $endpoint ) {
			if ( 0 === stripos( $endpoint, '/wp/v2' ) ) {
				$has_wp_route = true;
				break;
			}
		}

		$this->assertTrue( $has_wp_route );

	}

	function test_remove_core_endpoints() {

		$settings    = new REST_API_Toolbox_Settings();
		$common      = new REST_API_Toolbox_Common();
		$namespace   = $common->core_namespace();

		foreach( $common->core_endpoints() as $endpoint ) {

			$endpoint = '/' . $namespace . '/' . $endpoint;
			$remove_endpoint = 'remove-endpoint|' . $endpoint;

			$settings->change_enabled_setting( 'core', $remove_endpoint, true );

			$this->assertEquals( true, $settings->setting_is_enabled( 'core', $remove_endpoint ) );
			$this->assertFalse( $this->endpoint_exists( $endpoint ), $endpoint );

		}

	}

	function test_do_not_remove_core_endpoints() {

		$settings    = new REST_API_Toolbox_Settings();
		$common      = new REST_API_Toolbox_Common();
		$namespace   = $common->core_namespace();

		foreach( $common->core_endpoints() as $endpoint ) {

			$endpoint = '/' . $namespace . '/' . $endpoint;
			$remove_endpoint = 'remove-endpoint|' . $endpoint;

			$settings->change_enabled_setting( 'core', $remove_endpoint, false );

			$this->assertEquals( false, $settings->setting_is_enabled( 'core', $remove_endpoint ) );
			$this->assertTrue( $this->endpoint_exists( $endpoint ), $endpoint );

		}

	}

}

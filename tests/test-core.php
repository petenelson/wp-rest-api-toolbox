<?php

class REST_API_Toolbox_Test_Core extends REST_API_Toolbox_Test_Base {

	function test_remove_core() {

		//create_initial_rest_routes();

		REST_API_Toolbox_Settings::change_enabled_setting( 'core', 'remove-all-core-routes', true );

		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'core', 'remove-all-core-routes' ) );

		$wp_rest_server = REST_API_Toolbox_Common::get_rest_api_server();
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

		REST_API_Toolbox_Settings::change_enabled_setting( 'core', 'remove-all-core-routes', false );

		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'core', 'remove-all-core-routes' ) );

		$wp_rest_server = REST_API_Toolbox_Common::get_rest_api_server();
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

		$namespace   = REST_API_Toolbox_Common::core_namespace();

		foreach( REST_API_Toolbox_Common::core_endpoints() as $endpoint ) {

			$endpoint = '/' . $namespace . '/' . $endpoint;
			$remove_endpoint = 'remove-endpoint|' . $endpoint;

			REST_API_Toolbox_Settings::change_enabled_setting( 'core', $remove_endpoint, true );

			$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'core', $remove_endpoint ) );
			$this->assertFalse( $this->endpoint_exists( $endpoint ), $endpoint );

		}

	}

	function test_do_not_remove_core_endpoints() {

		$namespace   = REST_API_Toolbox_Common::core_namespace();

		foreach( REST_API_Toolbox_Common::core_endpoints() as $endpoint ) {

			$endpoint = '/' . $namespace . '/' . $endpoint;
			$remove_endpoint = 'remove-endpoint|' . $endpoint;

			REST_API_Toolbox_Settings::change_enabled_setting( 'core', $remove_endpoint, false );

			$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'core', $remove_endpoint ) );
			$this->assertTrue( $this->endpoint_exists( $endpoint ), $endpoint . ' does not exist' );

		}

	}

}

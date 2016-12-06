<?php

class REST_API_Toolbox_Test_General extends WP_UnitTestCase {

	function test_wp_version() {

		// Verfiy the version number code.
		$this->assertEquals( true, REST_API_Toolbox_Common::wp_version_at_least( '4.0' ) );

		// If I'm still alive when WP 10.0 comes out, I'll update it then.
		$this->assertEquals( false, REST_API_Toolbox_Common::wp_version_at_least( '10.0' ) );
	}

	function test_rest_api_disabled() {

		// Set the disabled setting to true.
		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', true );

		// Verfiy the setting.
		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );

		if ( REST_API_Toolbox_Common::wp_version_at_least( '4.7' ) ) {
			// Verify that the REST API is disabled via rest_authentication_errors filter.
			$this->assertInstanceOf( 'WP_Error', apply_filters( 'rest_authentication_errors', true ) );
		} else {
			// Verify that the REST API is disabled via rest_enabled filter.
			$this->assertFalse( apply_filters( 'rest_enabled', true ) );
		}
	}

	function test_rest_api_enabled() {

		// Set the disabled setting to false.
		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', false );

		// Verify the setting.
		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );

		if ( REST_API_Toolbox_Common::wp_version_at_least( '4.7' ) ) {
			// Verify that the REST API is not disabled via rest_authentication_errors filter.
			$this->assertNotInstanceOf( 'WP_Error', apply_filters( 'rest_authentication_errors', true ) );
		} else {
			// Verify that the REST API is not disabled via rest_enabled filter.
			$this->assertTrue( apply_filters( 'rest_enabled', true ) );
		}
	}

	function test_jsonp_disabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-jsonp', true );

		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-jsonp' ) );
		$this->assertEquals( false, apply_filters( 'rest_jsonp_enabled', false ) );
		$this->assertNotEquals( true, apply_filters( 'rest_jsonp_enabled', false ) );

	}

	function test_jsonp_enabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-jsonp', false );

		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-jsonp' ) );
		$this->assertEquals( true, apply_filters( 'rest_jsonp_enabled', true ) );
		$this->assertNotEquals( false, apply_filters( 'rest_jsonp_enabled', true ) );

	}
}


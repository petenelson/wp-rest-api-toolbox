<?php

class REST_API_Toolbox_Test_General extends WP_UnitTestCase {

	function test_rest_api_disabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', true );

		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( false, apply_filters( 'rest_enabled', false ) );

	}

	function test_rest_api_enabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', false );

		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( true, apply_filters( 'rest_enabled', true ) );

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


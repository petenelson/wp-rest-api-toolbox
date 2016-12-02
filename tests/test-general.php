<?php

class REST_API_Toolbox_Test_General extends WP_UnitTestCase {

	function test_rest_api_disabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', true );

<<<<<<< HEAD
		$this->assertEquals( true, $settings->setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->$this->assertInstanceOf( new WP_Error(), apply_filters( 'rest_authentication_errors', null ) );
=======
		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( false, apply_filters( 'rest_enabled', false ) );
>>>>>>> dabe5967af1c0182eb8faa67516134fa02b908ab

	}

	function test_rest_api_enabled() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'general', 'disable-rest-api', false );

<<<<<<< HEAD
		$this->assertEquals( false, $settings->setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( null, apply_filters( 'rest_authentication_errors', null ) );
=======
		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( true, apply_filters( 'rest_enabled', true ) );

>>>>>>> dabe5967af1c0182eb8faa67516134fa02b908ab
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


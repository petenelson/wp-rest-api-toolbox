<?php

class SampleTest extends WP_UnitTestCase {

	function test_rest_api_disabled() {

		$settings = new WP_REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'general', 'disable-rest-api', true );

		$this->assertEquals( true, $settings->setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( false, apply_filters( 'rest_enabled', false ) );

	}

	function test_rest_api_enabled() {

		$settings = new WP_REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'general', 'disable-rest-api', false );

		$this->assertEquals( false, $settings->setting_is_enabled( 'general', 'disable-rest-api' ) );
		$this->assertEquals( false, apply_filters( 'rest_enabled', false ) );

	}
}


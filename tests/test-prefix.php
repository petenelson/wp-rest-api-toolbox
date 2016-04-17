<?php

class REST_API_Toolbox_Test_Prefix extends REST_API_Toolbox_Test_Base {

	function test_changed_prefix() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_setting( 'general', 'rest-api-prefix', 'hello-world' );

		$this->assertEquals( 'hello-world', $settings->setting_get( 'general', 'rest-api-prefix' ) );

		$this->assertEquals( 'hello-world',  rest_get_url_prefix() );

	}

	function test_unchanged_prefix() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_setting( 'general', 'rest-api-prefix', '' );

		$this->assertEquals( '', $settings->setting_get( 'general', 'rest-api-prefix' ) );

		$this->assertEquals( 'wp-json',  rest_get_url_prefix() );

	}

	function test_sanitized_prefix() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_setting( 'general', 'rest-api-prefix', 'he||o /world<' ); // invalid URL, will be sanitized

		$this->assertEquals( 'heo-world', $settings->setting_get( 'general', 'rest-api-prefix' ) );

		$this->assertEquals( 'heo-world',  rest_get_url_prefix() );

	}

}

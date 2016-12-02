<?php

class REST_API_Toolbox_Test_Prefix extends REST_API_Toolbox_Test_Base {

	function sanitize_callback() {
		return 'REST_API_Toolbox_Settings_General::sanitize_general_settings';
	}

	function test_changed_prefix() {

		REST_API_Toolbox_Settings_General::change_setting( 'general', 'rest-api-prefix', 'hello-world', $this->sanitize_callback() );

		$this->assertEquals( 'hello-world', REST_API_Toolbox_Settings_General::setting_get( 'general', 'rest-api-prefix' ) );

		$this->assertEquals( 'hello-world',  rest_get_url_prefix() );

	}

	function test_unchanged_prefix() {

		REST_API_Toolbox_Settings_General::change_setting( 'general', 'rest-api-prefix', '', $this->sanitize_callback() );

		$this->assertEquals( '', REST_API_Toolbox_Settings_General::setting_get( 'general', 'rest-api-prefix' ), $this->sanitize_callback() );

		$this->assertEquals( 'wp-json',  rest_get_url_prefix() );

	}

	function test_sanitized_prefix() {

		REST_API_Toolbox_Settings_General::change_setting( 'general', 'rest-api-prefix', 'he||o /world<', $this->sanitize_callback() ); // invalid URL, will be sanitized

		$this->assertEquals( 'heo-world', REST_API_Toolbox_Settings_General::setting_get( 'general', 'rest-api-prefix' ) );

		$this->assertEquals( 'heo-world',  rest_get_url_prefix() );

	}

}

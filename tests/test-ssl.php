<?php

class REST_API_Toolbox_Test_SSL extends WP_UnitTestCase {

	function test_ssl_required() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'ssl', 'require-ssl', true );

		$this->assertEquals( true, $settings->setting_is_enabled( 'ssl', 'require-ssl' ) );

	}

	function test_ssl_not_required() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'ssl', 'require-ssl', false );

		$this->assertEquals( false, $settings->setting_is_enabled( 'ssl', 'require-ssl' ) );

	}
}

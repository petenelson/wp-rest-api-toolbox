<?php

class REST_API_Toolbox_Test_Core extends WP_UnitTestCase {

	function test_remove_core() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'core', 'remove-all-core-endpoints', true );

		$this->assertEquals( true, $settings->setting_is_enabled( 'core', 'remove-all-core-endpoints' ) );

		// TODO figure out how to install the REST API before running these tests

	}

	function test_do_not_remove_core() {

		$settings = new REST_API_Toolbox_Settings();
		$settings->change_enabled_setting( 'core', 'remove-all-core-endpoints', false );

		$this->assertEquals( false, $settings->setting_is_enabled( 'core', 'remove-all-core-endpoints' ) );

	}
}

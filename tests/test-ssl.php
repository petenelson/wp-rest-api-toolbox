<?php

class REST_API_Toolbox_Test_SSL extends WP_UnitTestCase {

	function test_ssl_required() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'ssl', 'require-ssl', true );

		$this->assertEquals( true, REST_API_Toolbox_Settings::setting_is_enabled( 'ssl', 'require-ssl' ) );

		// TODO figure out fake SSL request to local site to test

	}

	function test_ssl_not_required() {

		REST_API_Toolbox_Settings::change_enabled_setting( 'ssl', 'require-ssl', false );

		$this->assertEquals( false, REST_API_Toolbox_Settings::setting_is_enabled( 'ssl', 'require-ssl' ) );

	}
}

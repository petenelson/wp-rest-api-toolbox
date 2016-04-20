<?php

class REST_API_Toolbox_Test_Base extends WP_UnitTestCase {

	function endpoint_exists( $endpoint ) {

		$common = new REST_API_Toolbox_Common();
		return $common->endpoint_exists( $endpoint );

	}

}
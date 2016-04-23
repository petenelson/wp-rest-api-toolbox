<?php

class REST_API_Toolbox_Test_Base extends WP_UnitTestCase {

	function endpoint_exists( $endpoint ) {

		return REST_API_Toolbox_Common::endpoint_exists( $endpoint );

	}

}
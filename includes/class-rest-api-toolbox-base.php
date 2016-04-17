<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Base' ) ) {

	class REST_API_Toolbox_Base {

		var $settings;

		public function __construct() {
			$this->settings = new REST_API_Toolbox_Settings();
		}

	}

}

<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_i18n' ) ) {

	class REST_API_Toolbox_i18n {


		static public function plugins_loaded() {

			load_plugin_textdomain(
				'rest-api-toolbox',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}


	} // end class

}
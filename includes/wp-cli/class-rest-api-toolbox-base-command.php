<?php

class REST_API_Toolbox_Base_Command extends WP_CLI_Command  {

	public function change_enabled_setting( $key, $setting, $enabled ) {
		REST_API_Toolbox_Settings::change_enabled_setting( $key, $setting, $enabled );
	}

}

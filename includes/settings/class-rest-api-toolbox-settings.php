<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings' ) ) {

	class REST_API_Toolbox_Settings extends REST_API_Toolbox_Settings_Base {

		private $settings_key_core     = 'rest-api-toolbox-settings-core';
		private $settings_key_ssl      = 'rest-api-toolbox-settings-ssl';
		private $settings_key_help     = 'rest-api-toolbox-settings-help';


		public function plugins_loaded() {
			// admin menus
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_notices', array( $this, 'activation_admin_notice' ) );

			// filters to get plugin settings
			add_filter( 'rest-api-toolbox-setting-is-enabled', array( $this, 'setting_is_enabled' ), 10, 2 );
			add_filter( 'rest-api-toolbox-setting-get', array( $this, 'setting_get' ), 10, 3 );

		}


		public function activation_hook() {

			// add an option so we can show the activated admin notice
			add_option( 'rest-api-toolbox-plugin-activated', '1' );

		}


		public function activation_admin_notice() {
			if ( '1' === get_option( 'rest-api-toolbox-plugin-activated' ) ) {
				?>
					<div class="updated">
						<p>
							<?php echo wp_kses_post( sprintf( __( '<strong>REST API Toolbox activated!</strong> Please <a href="%s">visit the Settings page</a> to customize the settings.', 'rest-api-toolbox' ), esc_url( admin_url( 'options-general.php?page=rest-api-toolbox-settings' ) ) ) ); ?>
						</p>
					</div>
				<?php
				delete_option( 'rest-api-toolbox-plugin-activated' );
			}
		}


		public function deactivation_hook() {
			// placeholder in case we need deactivation code
		}


		public function admin_init() {

			foreach( $this->settings_keys() as $key => $title ) {
				// call_user_func( array( $this, "register_{$key}_settings" ), $title );
			}

			$this->register_help_tab();
		}




		private function register_core_settings( $title ) {
			$common = new REST_API_Toolbox_Common();
			$key = $this->settings_key_core;
			$this->plugin_settings_tabs[$key] = $title;

			register_setting( $key, $key, array( $this, 'sanitize_core_settings') );

			$section = 'core';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'remove-all-core-routes', __( 'Remove All WordPress Core Endpoints', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'remove-all-core-routes', 'after' => '' ) );

			$namespace = $common->core_namespace();
			$endpoints = $common->core_endpoints();

			foreach( $endpoints as $endpoint ) {
				$name = 'remove-endpoint|/' . $namespace . '/' . $endpoint;
				add_settings_field( $name, sprintf( __( 'Remove Endpoint: %s', 'rest-api-toolbox' ), $endpoint),
					array( $this, 'settings_yes_no' ),
					$key,
					$section,
					array( 'key' => $key, 'name' => $name, 'after' => '' )
					);
			}

		}


		private function register_ssl_settings( $title ) {
			$key = $this->settings_key_ssl;
			$this->plugin_settings_tabs[$key] = $title;

			register_setting( $key, $key, array( $this, 'sanitize_ssl_settings') );

			$section = 'ssl';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'require-ssl', __( 'Require SSL', 'rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'require-ssl', 'after' => '' ) );

		}


		public function sanitize_core_settings( $settings ) {

			return $settings;
		}


		public function sanitize_ssl_settings( $settings ) {

			return $settings;
		}


		private function register_help_tab() {
			$key = $this->settings_key_help;
			$this->plugin_settings_tabs[$key] =  __( 'Help' );
			register_setting( $key, $key );
			$section = 'help';
			add_settings_section( $section, '', array( $this, 'section_header' ), $key );
		}




		public function admin_menu() {
			add_options_page( 'REST API Toolbox ' . __( 'Settings' ), __( 'REST API Toolbox', 'rest-api-toolbox' ), 'manage_options', $this->settings_page, array( $this, 'options_page' ), 30 );
		}


		public function options_page() {

			$tab = $this->current_tab(); ?>
			<div class="wrap">
				<?php $this->plugin_options_tabs(); ?>
				<form method="post" action="options.php" class="options-form">
					<?php settings_fields( $tab ); ?>
					<?php do_settings_sections( $tab ); ?>
					<?php
						if ( $this->settings_key_help !== $tab ) {
							submit_button( __( 'Save Changes' ), 'primary', 'submit', true );
						}
					?>
				</form>
			</div>
			<?php

			$settings_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING );
			if ( ! empty( $settings_updated ) ) {
				do_action( 'rest-api-toolbox-settings-updated' );
				flush_rewrite_rules();
			}

		}


		private function current_tab() {
			$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			return empty( $current_tab ) ? 'rest-api-toolbox-settings-general' : $current_tab;
		}


		private function plugin_options_tabs() {
			$current_tab = $this->current_tab();
			var_dump( $this->plugin_settings_tabs );
			echo '<h2>' . __( 'Settings' ) . ' &rsaquo; REST API Toolbox</h2><h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . urlencode( $this->settings_page ) . '&tab=' . urlencode( $tab_key ) . '">' . esc_html( $tab_caption ) . '</a>';
			}
			echo '</h2>';
		}




	} // end class

}
<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings' ) ) {

	class REST_API_Toolbox_Settings extends REST_API_Toolbox_Settings_Base {


		public function plugins_loaded() {
			// admin menus
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

			echo '<h2>' . __( 'Settings' ) . ' &rsaquo; REST API Toolbox</h2><h2 class="nav-tab-wrapper">';

			$tabs = apply_filters( 'rest-api-toolbox-settings-tabs', array() );

			foreach ( $tabs as $tab_key => $tab_caption ) {
				$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . urlencode( $this->settings_page ) . '&tab=' . urlencode( $tab_key ) . '">' . esc_html( $tab_caption ) . '</a>';
			}
			echo '</h2>';
		}


	}

}
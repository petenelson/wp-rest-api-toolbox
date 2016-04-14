<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WP_REST_API_Toolbox_Settings' ) ) {

	class WP_REST_API_Toolbox_Settings {

		private $settings_page         = 'wp-rest-api-toolbox-settings';
		private $settings_key_general  = 'wp-rest-api-toolbox-settings-general';
		private $settings_key_core     = 'wp-rest-api-toolbox-settings-core';
		private $settings_key_ssl      = 'wp-rest-api-toolbox-settings-ssl';
		private $settings_key_help     = 'wp-rest-api-toolbox-settings-help';
		private $plugin_settings_tabs  = array();


		public function plugins_loaded() {
			// admin menus
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_notices', array( $this, 'activation_admin_notice' ) );

			// filters to get plugin settings
			add_filter( 'wp-rest-api-toolbox-setting-is-enabled', array( $this, 'setting_is_enabled' ), 10, 2 );
			add_filter( 'wp-rest-api-toolbox-setting-get', array( $this, 'setting_get' ), 10, 3 );

		}

		public function get_default_settings() {
			return array(
				'disable-rest-api'         => '0',
			);
		}


		public function activation_hook() {

			// create default settings
			add_option( $this->settings_key_general, $this->get_default_settings(), '', $autoload = 'no' );

			// add an option so we can show the activated admin notice
			add_option( 'wp-rest-api-toolbox-plugin-activated', '1' );

		}


		public function activation_admin_notice() {
			if ( '1' === get_option( 'wp-rest-api-toolbox-plugin-activated' ) ) {
				?>
					<div class="updated">
						<p>
							<?php echo wp_kses_post( sprintf( __( '<strong>REST API Toolbox activated!</strong> Please <a href="%s">visit the Settings page</a> to customize the settings.', 'wp-rest-api-toolbox' ), esc_url( admin_url( 'options-general.php?page=wp-rest-api-toolbox-settings' ) ) ) ); ?>
						</p>
					</div>
				<?php
				delete_option( 'wp-rest-api-toolbox-plugin-activated' );
			}
		}


		public function deactivation_hook() {
			// placeholder in case we need deactivation code
		}


		public function admin_init() {
			$this->register_general_settings();
			$this->register_core_settings();
			$this->register_ssl_settings();
			$this->register_help_tab();
		}


		private function register_general_settings() {
			$key = $this->settings_key_general;
			$this->plugin_settings_tabs[$key] = __( 'General' );

			register_setting( $key, $key, array( $this, 'sanitize_general_settings') );

			$section = 'general';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'disable-rest-api', __( 'Disable REST API', 'wp-rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-rest-api', 'after' => '' ) );

		}


		private function register_core_settings() {
			$key = $this->settings_key_core;
			$this->plugin_settings_tabs[$key] = __( 'Core', 'wp-rest-api-toolbox' );

			register_setting( $key, $key, array( $this, 'sanitize_core_settings') );

			$section = 'core';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'disable-all-core-endpoints', __( 'Disable WordPress Core Endpoints', 'wp-rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'disable-all-core-endpoints', 'after' => '' ) );

		}


		private function register_ssl_settings() {
			$key = $this->settings_key_ssl;
			$this->plugin_settings_tabs[$key] = __( 'SSL', 'wp-rest-api-toolbox' );

			register_setting( $key, $key, array( $this, 'sanitize_ssl_settings') );

			$section = 'ssl';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'require-ssl', __( 'Require SSL', 'wp-rest-api-toolbox' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'require-ssl', 'after' => '' ) );

		}

		public function sanitize_general_settings( $settings ) {

			return $settings;
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


		public function change_enabled_setting( $key, $setting, $enabled ) {
			$options_key = $this->options_key( $key, $setting );
			$option = get_option( $options_key );
			if ( false === $option ) {
				$option = array();
			}

			$option[ $setting ] = $enabled ? '1' : '0';

			update_option( $options_key, $option );
		}


		public function setting_is_enabled( $key, $setting ) {
			return '1' === $this->setting_get( $key, $setting, '0' );
		}


		public function setting_get( $key, $setting, $value ) {

			$args = wp_parse_args( get_option( $this->options_key( $key, $setting ) ),
				array(
					$setting => $value,
				)
			);

			return $args[ $setting ];
		}


		private function options_key( $key, $setting ) {
			return "{$this->settings_page}-{$key}";
		}


		public function settings_input( $args ) {

			extract( wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'maxlength' => 50,
					'size' => 30,
					'after' => '',
					'type' => 'text',
					'min' => 0,
					'max' => 0,
					'step' => 1,
				)
			) );


			$option = get_option( $key );
			$value = isset( $option[$name] ) ? esc_attr( $option[$name] ) : '';

			$min_max_step = '';
			if ( $type === 'number' ) {
				$min = intval( $args['min'] );
				$max = intval( $args['max'] );
				$step = intval( $args['step'] );
				$min_max_step = " step='{$step}' min='{$min}' max='{$max}' ";
			}

			echo "<div><input id='{$name}' name='{$key}[{$name}]'  type='{$type}' value='" . $value . "' size='{$size}' maxlength='{$maxlength}' {$min_max_step} /></div>";

			$this->output_after( $after );

		}


		public function settings_checkbox_list( $args ) {
			extract( wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'items' => array(),
					'after' => '',
					'legend' => '',
				)
			) );

			$option = get_option( $key );
			$values = isset( $option[$name] ) ? $option[$name] : '';
			if ( ! is_array( $values ) ) {
				$values = array();
			}

			?>
				<fieldset>
					<legend class="screen-reader-text">
						<?php echo esc_html( $legend ) ?>
					</legend>

					<?php foreach ( $items as $value => $value_dispay ) : ?>
						<label>
							<input type="checkbox" name="<?php echo $key ?>[<?php echo $name ?>][]" value="<?php echo $value ?>" <?php checked( in_array( $value, $values) ); ?> />
							<?php echo esc_html( $value_dispay ); ?>
						</label>
						<br/>
					<?php endforeach; ?>
				</fieldset>
			<?php

		}


		public function settings_textarea( $args ) {

			extract( wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'rows' => 10,
					'cols' => 40,
					'after' => '',
				)
			) );


			$option = get_option( $key );
			$value = isset( $option[$name] ) ? esc_attr( $option[$name] ) : '';

			echo "<div><textarea id='{$name}' name='{$key}[{$name}]' rows='{$rows}' cols='{$cols}'>" . $value . "</textarea></div>";

			$this->output_after( $after );

		}


		public function settings_yes_no( $args ) {

			extract( wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'after' => '',
				)
			) );

			$option = get_option( $key );
			$value = isset( $option[ $name ] ) ? esc_attr( $option[ $name ] ) : '';

			if ( empty( $value ) ) {
				$value = '0';
			}

			echo '<div>';
			echo "<label><input id='{$name}_1' name='{$key}[{$name}]'  type='radio' value='1' " . ( '1' === $value ? " checked=\"checked\"" : "" ) . "/>" . esc_html__( 'Yes' ) . "</label> ";
			echo "<label><input id='{$name}_0' name='{$key}[{$name}]'  type='radio' value='0' " . ( '0' === $value ? " checked=\"checked\"" : "" ) . "/>" . esc_html__( 'No' ) . "</label> ";
			echo '</div>';

			$this->output_after( $after );

		}


		private function output_after( $after ) {
			if ( ! empty( $after ) ) {
				echo '<div>' . wp_kses_post( $after ) . '</div>';
			}
		}


		public function admin_menu() {
			add_options_page( 'REST API Toolbox' . __( 'Settings' ), __( 'REST API Toolbox', 'wp-rest-api-toolbox' ), 'manage_options', $this->settings_page, array( $this, 'options_page' ), 30 );
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
				do_action( 'wp-rest-api-toolbox-flush-sizes-transient' );
			}

		}


		private function current_tab() {
			$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			return empty( $current_tab ) ? $this->settings_key_general : $current_tab;
		}


		private function plugin_options_tabs() {
			$current_tab = $this->current_tab();
			echo '<h2>' . __( 'Settings' ) . ' &rsaquo; REST API Toolbox</h2><h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . urlencode( $this->settings_page ) . '&tab=' . urlencode( $tab_key ) . '">' . esc_html( $tab_caption ) . '</a>';
			}
			echo '</h2>';
		}


		public function section_header( $args ) {

			switch ( $args['id'] ) {
				case 'help';
					include_once WP_REST_API_TOOLBOX_ROOT . 'admin/partials/admin-help.php';
					break;
			}

		}


	} // end class

}
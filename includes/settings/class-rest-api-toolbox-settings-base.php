<?php
if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Base' ) ) {

	class REST_API_Toolbox_Settings_Base {

		var $settings_page = 'rest-api-toolbox-settings';
		static $plugin_settings_tabs  = array();

		public function change_enabled_setting( $key, $setting, $enabled ) {
			if ( ! $this->settings_key_is_valid( $key ) ) {
				return false;
			}

			$options_key = $this->options_key( $key );
			$option = get_option( $options_key );
			if ( false === $option ) {
				$option = array();
			}

			$option[ $setting ] = $enabled ? '1' : '0';

			return update_option( $options_key, $option );
		}

		public function change_setting( $key, $setting, $value ) {
			if ( ! $this->settings_key_is_valid( $key ) ) {
				return false;
			}

			$options_key = $this->options_key( $key );
			$option = get_option( $options_key );
			if ( false === $option ) {
				$option = array();
			}

			$option[ $setting ] = $value;

			$option = call_user_func( array( $this, "sanitize_{$key}_settings" ), $option );

			return update_option( $options_key, $option );
		}


		public function settings_key_is_valid( $key ) {
			return in_array( $key, array_keys( $this->settings_keys() ) );
		}


		public function settings_keys() {
			return array(
				'general'  => __( 'General', 'rest-api-toolbox' ),
				'core'     => __( 'Core', 'rest-api-toolbox' ),
				'ssl'      => __( 'SSL', 'rest-api-toolbox' ),
			);
		}


		public function setting_is_enabled( $key, $setting ) {
			return '1' === $this->setting_get( $key, $setting, '0' );
		}


		public function setting_get( $key, $setting, $value = '' ) {

			$args = wp_parse_args( get_option( $this->options_key( $key ) ),
				array(
					$setting => $value,
				)
			);

			return $args[ $setting ];
		}


		public function options_key( $key ) {
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


		public function output_after( $after ) {
			if ( ! empty( $after ) ) {
				echo '<div>' . wp_kses_post( $after ) . '</div>';
			}
		}

		public function section_header( $args ) {

			switch ( $args['id'] ) {
				case 'help';
					include_once REST_API_TOOLBOX_ROOT . 'admin/partials/admin-help.php';
					break;
			}

		}

	}

}

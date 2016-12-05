<?php
if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'REST_API_Toolbox_Settings_Base' ) ) {

	class REST_API_Toolbox_Settings_Base {

		static $settings_page = 'rest-api-toolbox-settings';

		static public function change_enabled_setting( $key, $setting, $enabled ) {
			if ( ! self::settings_key_is_valid( $key ) ) {
				return false;
			}

			$options_key = self::options_key( $key );
			$option = get_option( $options_key );
			if ( false === $option ) {
				$option = array();
			}

			$option[ $setting ] = $enabled ? '1' : '0';

			return update_option( $options_key, $option );
		}

		static public function change_setting( $key, $setting, $value, $sanitize_callback = null ) {
			if ( ! self::settings_key_is_valid( $key ) ) {
				return false;
			}

			$options_key = self::options_key( $key );
			$option = get_option( $options_key );
			if ( false === $option ) {
				$option = array();
			}

			$option[ $setting ] = $value;

			if ( empty( $sanitize_callback ) ) {
				$sanitize_callback = array( __CLASS__, "sanitize_{$key}_settings" );
			}

			$option = call_user_func( $sanitize_callback, $option );

			return update_option( $options_key, $option );
		}


		static public function settings_key_is_valid( $key ) {
			return in_array( $key, array_keys( self::settings_keys() ) );
		}


		static public function settings_keys() {
			return array(
				'general'  => __( 'General', 'rest-api-toolbox' ),
				'core'     => __( 'Core', 'rest-api-toolbox' ),
				'ssl'      => __( 'SSL', 'rest-api-toolbox' ),
			);
		}


		static public function setting_is_enabled( $key, $setting ) {
			return '1' === self::setting_get( $key, $setting, '0' );
		}


		static public function setting_get( $key, $setting, $value = '' ) {


			$args = wp_parse_args( get_option( self::options_key( $key ) ),
				array(
					$setting => $value,
				)
			);

			return $args[ $setting ];
		}


		static public function options_key( $key ) {
			return self::$settings_page . "-{$key}";
		}

		static public function settings_input( $args ) {

			$args = wp_parse_args( $args,
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
			);

			$name      = $args['name'];
			$key       = $args['key'];
			$maxlength = $args['maxlength'];
			$size      = $args['size'];
			$after     = $args['after'];
			$type      = $args['type'];
			$min       = $args['min'];
			$max       = $args['max'];
			$step      = $args['step'];

			$option = get_option( $key );
			$value = isset( $option[ $name ] ) ? $option[ $name ] : '';

			$min_max_step = '';
			if ( $type === 'number' ) {
				$min = intval( $args['min'] );
				$max = intval( $args['max'] );
				$step = intval( $args['step'] );
				$min_max_step = " step='{$step}' min='{$min}' max='{$max}' ";
			}

			?>
				<div>
					<input
						id="<?php echo esc_attr( $name ); ?> "
						name="<?php echo esc_attr( "{$key}[{$name}]" ) ?>"
						type="<?php echo esc_attr( $type ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						size="<?php echo esc_attr( $size ); ?>"
						maxlength="<?php echo esc_attr( $maxlength ); ?>"
						{$min_max_step}
						/>
				</div>
			<?php

			self::output_after( $after );
		}

		static public function settings_yes_no( $args ) {

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

			self::output_after( $after );

		}


		static public function output_after( $after ) {
			if ( ! empty( $after ) ) {
				echo '<div>' . wp_kses_post( $after ) . '</div>';
			}
		}


	}

}

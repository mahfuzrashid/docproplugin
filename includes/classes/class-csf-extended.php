<?php
/**
 * Codestar Framework Extended
 */


if ( ! class_exists( 'CSF_Extended' ) ) {
	/**
	 * Class CSF_Extended
	 */
	class CSF_Extended extends CSF {

		/**
		 * Load CSF Extended
		 */
		public static function load() {

			if ( ! function_exists( 'get_current_screen' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
				require_once ABSPATH . 'wp-admin/includes/screen.php';
			}

			add_filter( 'csf_enqueue_assets', '__return_true' );
			add_action( 'wp_enqueue_scripts', array( 'CSF', 'add_admin_enqueue_scripts' ) );

			CSF::init();
		}

		/**
		 * Create frontend form
		 *
		 * @param $fields
		 * @param bool $echo
		 *
		 * @return string|void
		 */
		public static function createFrontendFormFields( $fields, $echo = true ) {

			if ( ! is_array( $fields ) || empty( $fields ) ) {
				return;
			}

			ob_start();

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! empty( $field['type'] ) ) {
						$classname = 'CSF_Field_' . $field['type'];
						CSF_Extended::maybe_include_field( $field['type'] );
						if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
							$instance = new $classname( $field );
							if ( method_exists( $classname, 'enqueue' ) ) {
								$instance->enqueue();
							}
							unset( $instance );
						}

						$field_value = isset( $field['value'] ) ? $field['value'] : '';

						CSF_Extended::field( $field, $field_value );
					}
				}
			}

			$fields_html = sprintf( '<div class="csf-onload">%s</div>', ob_get_clean() );

			if ( $echo ) {
				printf( '%s', $fields_html );

				return;
			}

			return $fields_html;
		}
	}

	CSF_Extended::load();
}
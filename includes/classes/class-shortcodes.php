<?php
/**
 * Shortcode class
 */


if ( ! class_exists( 'DOCPRO_Shortcodes' ) ) {
	/**
	 * Class DOCPRO_Shortcodes
	 *
	 * @property array $shortcodes
	 */
	final class DOCPRO_Shortcodes {
		/**
		 * DOCPRO_Shortcodes constructor.
		 */
		function __construct() {
			add_action( 'init', array( $this, 'register_shortcodes' ) );
			add_filter( 'the_content', array( $this, 'apply_shortcodes' ) );
		}


		/**
		 * Return array of all shortcodes
		 *
		 * @return mixed|void
		 */
		function get_shortcodes() {
			return apply_filters( 'docpro_filters_shortcodes', array(
				'docpro-profiles'     => array(
					'method' => array( __CLASS__, 'render_profiles' ),
				),
				'doctors'             => array(
					'template' => 'archive-doctor',
				),
				'clinics'             => array(
					'template' => 'archive-clinic',
				),
				'review-form'         => array(
					'template' => 'form-review',
				),
				'docpro-dashboard'    => array(
					'template' => 'dashboard',
				),
				'docpro-registration' => array(
					'template' => 'form-registration',
				),
			) );
		}


		/**
		 * Apply shortcodes
		 *
		 * @param $content
		 *
		 * @return string
		 */
		function apply_shortcodes( $content ) {

			switch ( get_the_ID() ) {
				case (int) docpro()->get_option( 'docpro_doctors_page' ) :
					$content = sprintf( '[doctors]' );
					break;

				case (int) docpro()->get_option( 'docpro_doctors_page_map' ) :
					$content = sprintf( '[doctors view="large-map"]' );
					break;

				case (int) docpro()->get_option( 'docpro_clinic_page' ) :
					$content = sprintf( '[clinics]' );
					break;

				case (int) docpro()->get_option( 'docpro_clinic_page_map' ) :
					$content = sprintf( '[clinics view="large-map"]' );
					break;

				case (int) docpro()->get_option( 'docpro_review_form_page' ) :
					$content = sprintf( '[review-form]' );
					break;

				case (int) docpro()->get_option( 'docpro_dashboard_page' ) :
					$content = sprintf( '[docpro-dashboard]' );
					break;

				case (int) docpro()->get_option( 'docpro_reg_page' ) :
					$content = sprintf( '[docpro-registration]' );
					break;
			}

			return $content;
		}


		/**
		 * Render profiles
		 *
		 * @return false|string
		 */
		static function render_profiles() {
			ob_start();

			global $doctor, $patient, $clinic;

			$doctor  = docpro_get_profile( 'doctor' );
			$patient = docpro_get_profile( 'patient' );
			$clinic  = docpro_get_profile( 'clinic' );

			if ( $doctor instanceof DOCPRO_User_doctor && ! empty( $doctor->ID ) ) {
				docpro_get_template( 'single-doctor.php' );
			} elseif ( $patient instanceof DOCPRO_User_patient && ! empty( $patient->ID ) ) {
				docpro_get_template( 'single-patient.php' );
			} elseif ( $clinic instanceof DOCPRO_User_clinic && ! empty( $clinic->ID ) ) {
				docpro_get_template( 'single-clinic.php' );
			}

			return ob_get_clean();
		}


		/**
		 * @param array $atts
		 * @param null $content
		 * @param string $shortcode
		 *
		 * @return false|string
		 */
		function render_shortcode( $atts = array(), $content = null, $shortcode = '' ) {

			global $url_data;

			ob_start();

			$url_data       = wp_unslash( $_GET );
			$shortcode_args = $url_data + (array) $atts + $this->get_shortcode( $shortcode );
			$template       = docpro()->get_args_option( 'template', '', $shortcode_args );

			if ( ! empty( $template ) ) {
				docpro_get_template( sprintf( '%s.php', $template ), $shortcode_args );
			}

			return ob_get_clean();
		}


		/**
		 * Return shortcode args by shortcode id
		 *
		 * @param $shortcode
		 *
		 * @return mixed|string
		 */
		private function get_shortcode( $shortcode ) {
			return docpro()->get_args_option( $shortcode, array(), $this->shortcodes );
		}


		/**
		 * Register Shortcodes
		 */
		function register_shortcodes() {
			$this->shortcodes = $this->get_shortcodes();

			foreach ( $this->shortcodes as $shortcode => $args ) {
				if ( isset( $args['method'] ) && ! empty( $method = $args['method'] ) ) {
					docpro()->pbSettings->register_shortcode( $shortcode, $method );
				} else {
					docpro()->pbSettings->register_shortcode( $shortcode, array( $this, 'render_shortcode' ) );
				}
			}
		}
	}

	docpro()->shortCodes = new DOCPRO_Shortcodes();
}
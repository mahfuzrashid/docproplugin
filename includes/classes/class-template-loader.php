<?php
/**
 * Class template loader
 */


if ( ! class_exists( 'DOCPRO_Template_loader' ) ) {
	/**
	 * Class DOCPRO_Template_loader
	 */
	class DOCPRO_Template_loader {

		/**
		 * Template loader init
		 */
		public static function init() {
			add_action( 'template_redirect', array( __CLASS__, 'unsupported_theme_init' ) );
		}


		/**
		 * @param $title
		 * @param $id
		 *
		 * @return string
		 */
		static function page_title_filter( $title, $id ) {

			global $doctor, $patient, $clinic, $wp;

			if ( docpro()->is_page( 'doctor', $id ) ) {
				return $doctor->display_name;
			} elseif ( docpro()->is_page( 'patient', $id ) ) {
				return $patient->display_name;
			} elseif ( docpro()->is_page( 'clinic', $id ) ) {
				return $clinic->display_name;
			}

			return $title;
		}


		/**
		 * Filter page title tag
		 *
		 * @param $title_parts
		 *
		 * @return mixed
		 */
		static function page_title_parts_filter( $title_parts ) {

			global $doctor, $patient, $clinic;

			if ( docpro()->is_page( 'doctor' ) ) {
				$title_parts['title'] = $doctor->display_name;
			} elseif ( docpro()->is_page( 'patient' ) ) {
				$title_parts['title'] = $patient->display_name;
			} elseif ( docpro()->is_page( 'clinic' ) ) {
				$title_parts['title'] = $clinic->display_name;
			}

			return $title_parts;
		}


		/**
		 * Add body classes
		 *
		 * @param $classes
		 *
		 * @return mixed
		 */
		static function add_body_classes( $classes ) {

			if ( docpro()->is_page( 'doctor' ) ) {
				$classes[] = 'single-doctor';
			} elseif ( docpro()->is_page( 'patient' ) ) {
				$classes[] = 'single-patient';
			} elseif ( docpro()->is_page( 'clinic' ) ) {
				$classes[] = 'single-clinic';
			}

			return $classes;
		}


		/**
		 * Plugin unsupported theme init
		 */
		static function unsupported_theme_init() {

			global $doctor, $patient, $clinic;

			if ( docpro()->is_page( 'doctor' ) ) {
				$doctor = docpro_get_profile( 'doctor' );
			} elseif ( docpro()->is_page( 'patient' ) ) {
				$patient = docpro_get_profile( 'patient' );
			} elseif ( docpro()->is_page( 'clinic' ) ) {
				$clinic = docpro_get_profile( 'clinic' );
			}

			// changing pages title
//			add_filter( 'the_title', array( __CLASS__, 'page_title_filter' ), 10, 2 );
			add_filter( 'document_title_parts', array( __CLASS__, 'page_title_parts_filter' ), 10, 1 );

			// add body classes
			add_filter( 'body_class', array( __CLASS__, 'add_body_classes' ), 10, 1 );
		}
	}
}
add_action( 'init', array( 'DOCPRO_Template_loader', 'init' ) );
<?php
/**
 * Class Functions
 *
 * @author Pluginbazar
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'DOCPRO_Functions' ) ) {
	/**
	 * Class DOCPRO_Functions
	 *
	 * @property PB_Settings $pbSettings;
	 * @property DOCPRO_Meta_boxes $metaBoxes;
	 * @property DOCPRO_Shortcodes $shortCodes;
	 */
	class DOCPRO_Functions {


		/**
		 * Return valid profile/user types
		 *
		 * @return mixed|void
		 */
		function get_profile_types() {
			return apply_filters( 'docpro_filters_profile_types', array(
				'doctor'  => esc_html__( 'Doctor', 'docpro' ),
				'clinic'  => esc_html__( 'Clinic', 'docpro' ),
				'patient' => esc_html__( 'Patient', 'docpro' ),
			) );
		}

		/**
		 * Return departments as array
		 *
		 * @return array
		 */
		function get_departments() {
			$_departments = docpro()->get_option( 'docpro_departments' );
			$_departments = empty( $_departments ) || ! is_array( $_departments ) ? array() : $_departments;
			$departments  = array();

			foreach ( $_departments as $department ) {
				if ( ! empty( $department_id = docpro()->get_args_option( 'id', '', $department ) ) ) {
					$departments[ $department_id ] = docpro()->get_args_option( 'name', '', $department );
				}
			}

			return $departments;
		}


		/**
		 * Return of all query vars
		 *
		 * @return mixed|void
		 */
		function get_query_vars() {
			$query_vars = array(
				'doctor'  => EP_ROOT | EP_PAGES,
				'clinic'  => EP_ROOT | EP_PAGES,
				'patient' => EP_ROOT | EP_PAGES,
				'content' => EP_ROOT | EP_PAGES,
			);

			return apply_filters( 'docpro_query_vars', $query_vars );
		}


		/**
		 * Return booking product ID
		 *
		 * @return mixed|string|void
		 */
		function get_booking_product_id() {
			return (int) $this->get_option( 'docpro_booking_product', 0 );
		}


		/**
		 * Return users per page
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		function get_users_per_page( $args = array() ) {

			switch ( docpro()->get_args_option( 'role', 'doctor', $args ) ) {
				case 'clinic' :
					$option_key = 'docpro_clinic_items_per_page';
					break;

				default :
					$option_key = 'docpro_doctors_items_per_page';
					break;
			}

			return apply_filters( 'docpro_filters_users_per_page', $this->get_option( $option_key, 10 ) );
		}


		/**
		 * Return doctors list
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		function get_doctors_list( $args = array() ) {
			$defaults = array(
				'role' => 'doctor',
			);
			$doctors  = array();
			$_doctors = get_users( wp_parse_args( $args, $defaults ) );

			foreach ( $_doctors as $doctor ) {
				if ( $doctor instanceof WP_User ) {
					$doctors[ $doctor->ID ] = $doctor->display_name;
				}
			}

			return $doctors;
		}


		function get_post_type_supports() {
//			'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'
		}


		/**
		 * Return page id
		 *
		 * @param string $page_for
		 *
		 * @return int
		 */
		function get_page_id( $page_for = '' ) {
			$page_id = 0;

			switch ( $page_for ) {
				case 'profiles' :
					$page_id = get_option( 'docpro_page_profiles' );


			}

			return (int) $page_id;
		}


		/**
		 * Check a page and return boolean
		 *
		 * @param string $checking_for
		 * @param false $page_id
		 *
		 * @return bool
		 */
		function is_page( $checking_for = '', $page_id = false ) {

			$page_id = ! $page_id || empty( $page_id ) ? get_the_ID() : $page_id;
			$is_page = false;

			if ( empty( $checking_for ) || ! $page_id || empty( $page_id ) ) {
				return false;
			}

			switch ( $checking_for ) {
				case 'profiles' :
					if ( $page_id == $this->get_option( 'docpro_page_profiles' ) ) {
						$is_page = true;
					}
					break;

				case 'doctor' :
					if ( get_query_var( 'pagename' ) == 'profiles' && ! empty( get_query_var( 'doctor' ) ) ) {
						$is_page = true;
					}
					break;

				case 'patient' :
					if ( get_query_var( 'pagename' ) == 'profiles' && ! empty( get_query_var( 'patient' ) ) ) {
						$is_page = true;
					}
					break;

				case 'clinic' :
					if ( get_query_var( 'pagename' ) == 'profiles' && ! empty( get_query_var( 'clinic' ) ) ) {
						$is_page = true;
					}
					break;
			}


			return $is_page;
		}


		/**
		 * Return all countries and states as group
		 *
		 * @return mixed|void
		 */
		function get_all_countries_states() {

			$countries        = $this->get_countries();
			$countries_states = array();

			foreach ( array_filter( $this->get_states() ) as $country_code => $states ) {

				if ( ! is_array( $states ) ) {
					continue;
				}

				$country_label  = $this->get_args_option( $country_code, '', $countries );
				$single_country = array();

				foreach ( $states as $state_code => $state_label ) {
					$single_country[ $country_code . '#' . $state_code ] = $country_label . ' - ' . $state_label;
				}

				$countries_states[ $country_label ] = $single_country;
			}

			return apply_filters( 'docpro_filters_all_countries_states', $countries_states );
		}


		/**
		 * Return states with country code
		 *
		 * @return mixed
		 */
		function get_states() {
			return include DOCPRO_PLUGIN_DIR . 'i18n/states.php';
		}


		/**
		 * Return countries as array
		 *
		 * @return array
		 */
		function get_countries() {
			return include DOCPRO_PLUGIN_DIR . 'i18n/countries.php';
		}


		/**
		 * Return location capabilities as array
		 *
		 * @return mixed|void
		 */
		function get_location_capabilities() {
			return apply_filters( 'docpro_filters_location_capabilities', array(
				'edit_post'              => 'edit_location',
				'read_post'              => 'read_location',
				'delete_post'            => 'delete_location',
				'edit_posts'             => 'edit_locations',
				'edit_others_posts'      => 'edit_others_locations',
				'edit_published_posts'   => 'edit_published_locations',
				'publish_posts'          => 'publish_locations',
				'delete_posts'           => 'delete_locations',
				'delete_others_posts'    => 'delete_others_locations',
				'delete_published_posts' => 'delete_published_locations',
				'delete_private_posts'   => 'delete_private_locations',
				'edit_private_posts'     => 'edit_private_locations',
				'read_private_posts'     => 'read_private_locations',
			) );
		}


		/**
		 * Return plugin settings page as array
		 *
		 * @return mixed|void
		 */
		function get_plugin_settings() {

			$page_options  = array( '' => esc_html__( 'Select page', 'docpro' ) ) + docpro()->pbSettings->generate_args_from_string( 'PAGES', array() );
			$contact_forms = array( '' => esc_html__( 'Select form', 'docpro' ) ) + docpro()->pbSettings->generate_args_from_string( 'POSTS_%wpcf7_contact_form%', array() );

			// Settings Section
			$sections[] = array(
				'id'     => 'docpro_options',
				'title'  => esc_html__( 'Settings', 'docpro' ),
				'fields' => array(
					array(
						'id'          => 'docpro_gmap_api',
						'title'       => esc_html__( 'Google Map API Key', 'docpro' ),
						'desc'        => esc_html__( 'Enter your google map api key here.', 'docpro' ),
						'placeholder' => esc_attr( 'AIzaSyD5dHX3A9YYi2gK4AgkcusPOuOn4pIkj6Kg' ),
						'type'        => 'text',
					),
					array(
						'id'          => 'docpro_map_zoom',
						'title'       => esc_html__( 'Google Map Zoom', 'docpro' ),
						'desc'        => esc_html__( 'Enter zoom value for Google Map. Default: 5', 'docpro' ),
						'placeholder' => esc_attr( '10' ),
						'type'        => 'number',
					),
					array(
						'id'      => 'docpro_review_form_page',
						'type'    => 'select',
						'title'   => esc_html__( 'Review form page', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'      => 'docpro_reg_page',
						'type'    => 'select',
						'title'   => esc_html__( 'Registration page', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'           => 'docpro_departments',
						'type'         => 'repeater',
						'title'        => esc_html__( 'Departments', 'docpro' ),
						'button_title' => esc_html__( 'Add Department', 'docpro' ),
						'fields'       => array(
							array(
								'id'          => 'id',
								'type'        => 'text',
								'title'       => esc_html__( 'ID', 'docpro' ),
								'placeholder' => esc_html__( 'Cardiology', 'docpro' ),
							),
							array(
								'id'          => 'name',
								'type'        => 'text',
								'title'       => esc_html__( 'Name', 'docpro' ),
								'placeholder' => esc_html__( 'Cardiology', 'docpro' ),
							),
						),
						'default'      => array( '' ),
					),
				),
			);

			// Settings - Booking Section
			$sections[] = array(
//				'parent' => 'docpro_options',
				'title'  => esc_html__( 'Booking Options', 'docpro' ),
				'fields' => array(
					array(
						'id'    => 'docpro_enable_payment',
						'title' => esc_html__( 'Enable Payment Receive', 'docpro' ),
						'desc'  => esc_html__( 'You must install and activate WooCommerce to get this support.', 'docpro' ),
						'type'  => 'switcher',
					),
					array(
						'id'      => 'docpro_dashboard_page',
						'type'    => 'select',
						'title'   => esc_html__( 'Dashboard page', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'         => 'docpro_booking_product',
						'title'      => esc_html__( 'Select Booking Product', 'docpro' ),
						'desc'       => esc_html__( 'You must select a booking product to take payment.', 'docpro' ),
						'type'       => 'select',
						'options'    => 'posts',
						'chosen'     => true,
						'query_args' => array(
							'post_type' => 'product',
						),
					),
				),
			);


			// Doctors Settings Section
			$sections[] = array(
				'id'     => 'docpro_settings_doctors',
				'title'  => esc_html__( 'Doctors Settings', 'docpro' ),
				'fields' => array(
					array(
						'id'      => 'docpro_doctors_page',
						'type'    => 'select',
						'title'   => esc_html__( 'Doctors page (General View)', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'      => 'docpro_doctors_page_map',
						'type'    => 'select',
						'title'   => esc_html__( 'Doctors page (Map View)', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'          => 'docpro_doctors_items_per_page',
						'type'        => 'number',
						'title'       => esc_html__( 'Items per page', 'docpro' ),
						'placeholder' => 10,
					),
				)
			);


			// Clinic Settings Section
			$sections[] = array(
				'id'     => 'docpro_settings_clinic',
				'title'  => esc_html__( 'Clinic Settings', 'docpro' ),
				'fields' => array(
					array(
						'id'      => 'docpro_clinic_page',
						'type'    => 'select',
						'title'   => esc_html__( 'Clinic page (General View)', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'      => 'docpro_clinic_page_map',
						'type'    => 'select',
						'title'   => esc_html__( 'Clinic page (Map View)', 'docpro' ),
						'options' => $page_options,
					),
					array(
						'id'          => 'docpro_clinic_items_per_page',
						'type'        => 'number',
						'title'       => esc_html__( 'Items per page', 'docpro' ),
						'placeholder' => 10,
					),
					array(
						'id'      => 'docpro_clinic_contact_form',
						'type'    => 'select',
						'title'   => esc_html__( 'Clinic Contact Form', 'docpro' ),
						'options' => $contact_forms,
					),
				)
			);


			return apply_filters( 'docpro_filters_plugin_settings_sections', $sections );
		}


		/**
		 * Print notices
		 *
		 * @param string $message
		 * @param string $type
		 * @param bool $is_dismissible
		 */
		function print_notice( $message = '', $type = 'success', $is_dismissible = true ) {

			$is_dismissible = $is_dismissible ? 'is-dismissible' : '';

			if ( ! empty( $message ) ) {
				printf( '<div class="docpro-notice notice notice-%s %s"><p>%s</p></div>', $type, $is_dismissible, $message );
			}
		}


		/**
		 * Return Post Meta Value
		 *
		 * @param bool $meta_key
		 * @param bool $post_id
		 * @param string $default
		 * @param bool $singular
		 *
		 * @return mixed|string|void
		 */
		function get_meta( $meta_key = false, $post_id = false, $default = '', $singular = true ) {

			if ( ! $meta_key ) {
				return '';
			}

			$post_id    = ! $post_id ? get_the_ID() : $post_id;
			$meta_value = get_post_meta( $post_id, $meta_key, $singular );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'docpro_filters_get_meta', $meta_value, $meta_key, $post_id, $default, $singular );
		}


		/**
		 * Return option value
		 *
		 * @param string $option_key
		 * @param string $default_val
		 *
		 * @return mixed|string|void
		 */
		function get_option( $option_key = '', $default_val = '' ) {

			if ( empty( $option_key ) ) {
				return '';
			}

			$docpro_settings = get_option( 'docpro_settings' );
			$option_val      = $this->get_args_option( $option_key, $default_val, $docpro_settings );
			$option_val      = is_array( $default_val ) && empty( $option_val ) ? array() : $option_val;
			$option_val      = ! is_array( $default_val ) && empty( $option_val ) ? $default_val : $option_val;

			return apply_filters( 'docpro_filters_option_' . $option_key, $option_val );
		}


		/**
		 * Return PB_Settings class
		 *
		 * @param array $args
		 *
		 * @return PB_Settings
		 */
		function PB_Settings( $args = array() ) {

			return new PB_Settings( $args );
		}


		/**
		 * Return Arguments Value
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		function get_args_option( $key = '', $default = '', $args = array() ) {

			global $this_preloader;

			$args    = empty( $args ) ? $this_preloader : $args;
			$default = empty( $default ) ? '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}
	}
}

global $docpro;

$docpro = new DOCPRO_Functions();
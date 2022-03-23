<?php
/**
 * Class Doctor
 */


if ( ! class_exists( 'DOCPRO_User_doctor' ) ) {
	/**
	 * Class DOCPRO_User_doctor
	 *
	 * @property array $specialities
	 * @property array $educations
	 * @property array $services
	 * @property array $awards
	 * @property array $experiences
	 * @property array $skills
	 * @property string $visiting_time
	 */
	final class DOCPRO_User_doctor extends DOCPRO_User_base {
		/**
		 * DOCPRO_User_doctor constructor.
		 *
		 * @param int $id
		 * @param string $name
		 * @param string $site_id
		 */
		public function __construct( $id = 0, $name = '', $site_id = '' ) {
			parent::__construct( $id, $name, $site_id );
		}


		/**
		 * Return all patient for this doctor
		 *
		 * @return array
		 */
		function get_all_patients() {

			$patients = [];

			foreach ( $this->get_booking_ids() as $booking_id ) {
				if ( $patient_id = docpro()->get_meta( '_patient_id', $booking_id ) ) {
					$patients[ $patient_id ] = docpro_get_profile( 'patient', $patient_id );
				}
			}

			return $patients;
		}


		/**
		 * Return booking ids for this doctor
		 *
		 * @param array $args
		 *
		 * @return int[]|WP_Post[]
		 */
		function get_booking_ids( $args = array() ) {

			$default              = array(
				'post_type'      => 'booking',
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'fields'         => 'ids',
				'meta_query'     => array(),
			);
			$args                 = wp_parse_args( $args, $default );
			$args['meta_query'][] = array(
				'key'     => '_doctor_id',
				'value'   => $this->ID,
				'compare' => '=',
			);

			return get_posts( apply_filters( 'docpro_filters_doctor_booking_query_args', $args ) );
		}


		/**
		 * Return dashboard navigation items
		 *
		 * @return array|mixed|void
		 */
		function get_dashboard_navigation() {
			return apply_filters( 'docpro_doctors_navigation_items', array(
				''             => sprintf( '<i class="fas fa-columns"></i> %s', esc_html__( 'Dashboard', 'docpro' ) ),
				'appointments' => sprintf( '<i class="fas fa-calendar-alt"></i> %s', esc_html__( 'Appointments', 'docpro' ) ),
				'patients'     => sprintf( '<i class="fas fa-wheelchair"></i> %s', esc_html__( 'My Patients', 'docpro' ) ),
//				'schedules'    => sprintf( '<i class="fas fa-clock"></i> %s', esc_html__( 'Schedules', 'docpro' ) ),
				'reviews'      => sprintf( '<i class="fas fa-star"></i> %s', esc_html__( 'Reviews', 'docpro' ) ),
//				'messages'     => sprintf( '<i class="fas fa-comments"></i> %s', esc_html__( 'Messages', 'docpro' ) ),
				'profile'      => sprintf( '<i class="fas fa-user"></i> %s', esc_html__( 'My Profile', 'docpro' ) ),
				'password'     => sprintf( '<i class="fas fa-unlock-alt"></i> %s', esc_html__( 'Change Password', 'docpro' ) ),
				'logout'       => sprintf( '<i class="fas fa-sign-out-alt"></i> %s', esc_html__( 'Logout', 'docpro' ) ),
			) );
		}


		/**
		 * Return service details by service id
		 *
		 * @param string $service_id
		 * @param string $return
		 * @param string $default
		 *
		 * @return array|false|mixed|string
		 */
		function get_service_details( $service_id = '', $return = '', $default = '' ) {

			if ( empty( $service_id ) || ! $service_id || ! is_array( $this->services ) ) {
				return false;
			}

			$service = array();
			foreach ( $this->services as $s ) {
				if ( docpro()->get_args_option( 'id', '', $s ) == $service_id && empty( $service ) ) {
					$service = $s;
				}
			}

			if ( empty( $return ) ) {
				return $service;
			}

			return docpro()->get_args_option( $return, $default, $service );
		}


		/**
		 * Return primary speciality
		 *
		 * @return mixed|string
		 */
		function get_primary_speciality() {

			$specialities = docpro_array_map( 'name', $this->specialities );
			$specialities = ! is_array( $specialities ) ? array() : $specialities;

			return reset( $specialities );
		}


		/**
		 * Get Designation HTML
		 *
		 * @return mixed|void
		 */
		function get_designation_html() {

			$html = array();

			if ( ! empty( $degrees_text = $this->get_degrees_text() ) ) {
				$html[] = sprintf( '<span>%s</span>', $degrees_text );
			}

			if ( ! empty( $html ) ) {
				$html[] = sprintf( '<span> - </span>' );
			}

			if ( ! empty( $primary_speciality = $this->get_primary_speciality() ) ) {
				$html[] = sprintf( '<span>%s</span>', $primary_speciality );
			}

			return apply_filters( 'docpro_filters_designation_html', implode( '', $html ), $html );
		}


		/**
		 * Return degrees as text
		 *
		 * @param string $separator
		 *
		 * @return mixed|void
		 */
		function get_degrees_text( $separator = ', ' ) {
			return apply_filters( 'docpro_filters_degrees_text', implode( $separator, $this->get_degrees() ) );
		}


		/**
		 * Return degrees as array
		 *
		 * @return array|string[]
		 */
		function get_degrees() {
			return docpro_array_map( 'degree', $this->educations );
		}


		/**
		 * Set primary data for a doctor
		 */
		function set_data() {
			foreach ( docpro()->metaBoxes->get_field_ids() as $field_id ) {
				$this->{ltrim( $field_id, '_' )} = $this->get( $field_id );
			}

			$this->type = 'doctor';
		}
	}
}
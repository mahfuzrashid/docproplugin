<?php
/**
 * Class Patient
 */


if ( ! class_exists( 'DOCPRO_User_patient' ) ) {
	/**
	 * Class DOCPRO_User_patient
	 *
	 * @property string $dob
	 * @property string $gender
	 * @property string $age
	 * @property string $blood_group
	 * @property string $marital_status
	 * @property string $note
	 * @property array $social_profiles
	 */
	final class DOCPRO_User_patient extends DOCPRO_User_base {
		/**
		 * DOCPRO_User_patient constructor.
		 *
		 * @param int $id
		 * @param string $name
		 * @param string $site_id
		 */
		public function __construct( $id = 0, $name = '', $site_id = '' ) {
			parent::__construct( $id, $name, $site_id );
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
				'key'     => '_patient_id',
				'value'   => $this->ID,
				'compare' => '=',
			);

			return get_posts( apply_filters( 'docpro_filters_patient_booking_query_args', $args ) );
		}


		/**
		 * Return dashboard navigation items
		 *
		 * @return array|mixed|void
		 */
		function get_dashboard_navigation() {
			return apply_filters( 'docpro_patient_navigation_items', array(
				''             => sprintf( '<i class="fas fa-columns"></i> %s', esc_html__( 'Dashboard', 'docpro' ) ),
				'appointments' => sprintf( '<i class="fas fa-calendar-alt"></i> %s', esc_html__( 'My Appointments', 'docpro' ) ),
				'favourites'   => sprintf( '<i class="fas fa-heart"></i> %s', esc_html__( 'Favourite Doctors', 'docpro' ) ),
//				'messages'    => sprintf( '<i class="fas fa-comments"></i> %s', esc_html__( 'Messages', 'docpro' ) ),
				'profile'      => sprintf( '<i class="fas fa-user"></i> %s', esc_html__( 'My Profile', 'docpro' ) ),
				'password'     => sprintf( '<i class="fas fa-unlock-alt"></i> %s', esc_html__( 'Change Password', 'docpro' ) ),
				'logout'       => sprintf( '<i class="fas fa-sign-out-alt"></i> %s', esc_html__( 'Logout', 'docpro' ) ),
			) );
		}


		/**
		 * Set primary data for a doctor
		 */
		function set_data() {
			$this->type = 'patient';

			foreach ( docpro()->metaBoxes->get_field_ids( $this->type ) as $field_id ) {
				$this->{ltrim( $field_id, '_' )} = $this->get( $field_id );
			}
		}
	}
}
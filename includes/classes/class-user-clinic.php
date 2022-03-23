<?php
/**
 * Class Clinic
 */


if ( ! class_exists( 'DOCPRO_User_clinic' ) ) {
	/**
	 * Class DOCPRO_User_clinic
	 *
	 * @property string $logo
	 * @property string $slogan
	 * @property string $about
	 * @property array $specifications
	 * @property array $services
	 * @property array $awards
	 * @property array $gallery
	 * @property array $doctors
	 * @property string $fax
	 * @property array $social_profiles
	 */
	final class DOCPRO_User_clinic extends DOCPRO_User_base {
		/**
		 * DOCPRO_User_clinic constructor.
		 *
		 * @param int $id
		 * @param string $name
		 * @param string $site_id
		 */
		public function __construct( $id = 0, $name = '', $site_id = '' ) {
			parent::__construct( $id, $name, $site_id );
		}


		/**
		 * Return dashboard navigation items
		 *
		 * @return array|mixed|void
		 */
		function get_dashboard_navigation() {
			return apply_filters( 'docpro_clinic_navigation_items', array(
				''         => sprintf( '<i class="fas fa-user"></i> %s', esc_html__( 'My Profile', 'docpro' ) ),
				'password' => sprintf( '<i class="fas fa-unlock-alt"></i> %s', esc_html__( 'Change Password', 'docpro' ) ),
				'logout'   => sprintf( '<i class="fas fa-sign-out-alt"></i> %s', esc_html__( 'Logout', 'docpro' ) ),
			) );
		}


		/**
		 * Return contact form ID
		 *
		 * @return mixed|string|void
		 */
		function get_contact_form_id() {
			return docpro()->get_option( 'docpro_clinic_contact_form' );
		}


		/**
		 * Return logo as avatar url
		 *
		 * @return false|string
		 */
		function get_avatar_url() {

			if ( $this->type === 'clinic' ) {
				return docpro()->get_args_option( 'url', '', $this->logo );
			}

			return parent::get_avatar_url();
		}


		/**
		 * Set primary data for a doctor
		 */
		function set_data() {
			$this->type = 'clinic';

			foreach ( docpro()->metaBoxes->get_field_ids( $this->type ) as $field_id ) {
				$this->{ltrim( $field_id, '_' )} = $this->get( $field_id );
			}

			$this->gallery = explode( ',', (string) $this->gallery );
		}
	}
}
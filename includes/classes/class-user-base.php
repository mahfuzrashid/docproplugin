<?php
/**
 * Class user base
 */

if ( ! class_exists( 'DOCPRO_User_base' ) ) {

	/**
	 * Class DOCPRO_User_base
	 *
	 * @property string $profile_photo
	 * @property array $locations
	 * @property string $type
	 * @property string $phone
	 * @property string $address
	 * @property boolean $is_verified
	 * @property boolean _availability
	 */
	class DOCPRO_User_base extends WP_User {


		/**
		 * DOCPRO_User_base constructor.
		 *
		 * @param int $id
		 * @param string $name
		 * @param string $site_id
		 */
		function __construct( $id = 0, $name = '', $site_id = '' ) {
			parent::__construct( $id, $name, $site_id );

			$this->set_data();
		}


		function get_map_view_url() {
			return '';
		}


		/**
		 * Return user type
		 *
		 * @return mixed|void
		 */
		function get_role() {
			return apply_filters( 'docpro_filters_user_role', reset( $this->roles ) );
		}


		/**
		 * Render is available html
		 *
		 * @param string $tag
		 * @param bool $echo
		 *
		 * @return string|void
		 */
		function is_available_html( $tag = 'li', $echo = true ) {

			$is_available_html = '';

			if ( $this->_availability ) {
				$is_available_html = sprintf( '<%1$s class="tt--top" aria-label="%2$s"><i class="icon-Trust-1"></i></%1$s>', $tag, esc_html__( 'Available', 'docpro' ) );
			}

			if ( $echo ) {
				printf( '%s', $is_available_html );

				return;
			}

			return $is_available_html;
		}


		/**
		 * Render verified html
		 *
		 * @param string $tag
		 * @param bool $echo
		 *
		 * @return string|void
		 */
		function is_verified_html( $tag = 'li', $echo = true ) {

			$is_available_html = '';

			if ( $this->is_verified ) {
				$is_available_html = sprintf( '<%1$s class="tt--top" aria-label="%2$s"><i class="icon-Trust-2"></i></%1$s>', $tag, esc_html__( 'Verified', 'docpro' ) );
			}

			if ( $echo ) {
				printf( '%s', $is_available_html );

				return;
			}

			return $is_available_html;
		}


		/**
		 * Return favourites
		 *
		 * @param array $args
		 *
		 * @return array
		 */
		function get_favourites( $args = array() ) {

			$defaults   = array(
				'number'     => - 1,
				'meta_query' => array(
					array(
						'key'     => '_followers',
						'value'   => $this->ID,
						'compare' => 'IN',
					),
				),
			);
			$query_args = wp_parse_args( $args, $defaults );

			return docpro_get_archive_query( apply_filters( 'docpro_filters_followers_query_args', $query_args ) )->get_results();
		}


		/**
		 * Update user data
		 *
		 * @param array $data
		 *
		 * @return bool
		 */
		function update_data( $data = array() ) {

			if ( ! is_array( $data ) ) {
				return false;
			}

			foreach ( $data as $key => $val ) {
				update_user_meta( $this->ID, $key, $val );
			}

			return true;
		}


		/**
		 * Return average review rating for this user
		 *
		 * @return float|int
		 */
		function get_average_review_rating() {
			if ( ! $this->has_review() ) {
				return 0;
			}

			$review_rating = array_map( function ( $review ) {
				if ( ! empty( $review ) ) {
					return docpro()->get_args_option( 'star', 0, $review );
				}

				return 0;
			}, $this->get_reviews() );
			$review_rating = array_sum( $review_rating ) / $this->get_reviews_count();

			return round( $review_rating, 1 );
		}


		/**
		 * Return boolean if a user has any reviews or not
		 *
		 * @return bool
		 */
		function has_review() {
			return $this->get_reviews_count() > 0;
		}


		/**
		 * Return total number reviews of this user
		 *
		 * @return int
		 */
		function get_reviews_count() {
			return count( $this->get_reviews() );
		}


		/**
		 * Return all reviews of a user
		 *
		 * @return false|mixed|void
		 */
		function get_reviews() {
			$reviews = $this->get_meta( '_reviews', array() );

			return is_array( $reviews ) && isset( $reviews[0] ) && empty( $reviews[0] ) ? array() : $reviews;
		}


		/**
		 * Return review form page url
		 *
		 * @return mixed|void
		 */
		function get_review_form_url() {

			$page_url = '';

			if ( ! empty( $review_page_id = (int) docpro()->get_option( 'docpro_review_form_page' ) ) ) {
				$page_url = sprintf( '%s?%s', get_permalink( $review_page_id ), http_build_query( array( 'id' => $this->ID ) ) );
			}

			return apply_filters( 'docpro_filters_review_form_url', $page_url );
		}


		/**
		 * Return available status as string
		 *
		 * @return string
		 */
		function get_availability_status() {

			if ( ! empty( $this->availability ) && $this->availability ) {
				return esc_html__( 'Available', 'docpro' );
			}

			return esc_html__( 'Not Available', 'docpro' );
		}


		/**
		 * Return locations
		 *
		 * @return array
		 */
		function get_locations() {

			if ( is_array( $this->locations ) && ! empty( $this->locations ) ) {
				return array_values( array_unique( $this->locations ) );
			}

			return array();
		}


		/**
		 * Return primary location formatted address
		 *
		 * @return mixed|void
		 */
		function get_primary_location_formatted() {

			$location = $this->get_primary_location();

			if ( $location instanceof DOCPRO_Location ) {
				return $location->get_formatted_address();
			}

			return '';
		}


		/**
		 * Return users primary location
		 *
		 * @return DOCPRO_Location|string
		 */
		function get_primary_location() {
			if ( empty( $locations = $this->locations ) ) {
				return '';
			}
			$location_id = reset( $locations );

			return docpro_get_location( $location_id );
		}


		/**
		 * Return profile permalink
		 *
		 * @param string $tab
		 *
		 * @return mixed|void
		 */
		function get_profile_permalink( $tab = '' ) {

			$profile_page   = get_post( docpro()->get_page_id( 'profiles' ) );
			$permalink_args = array(
				'base'     => get_site_url(),
				'page'     => $profile_page->post_name,
				'type'     => empty( $this->type ) ? reset( $this->roles ) : $this->type,
				'username' => $this->user_login,
			);

			if ( ! empty( $tab ) ) {
				$permalink_args['tab'] = $tab;
			}

			$permalink_args = apply_filters( 'docpro_filters_profile_permalink_args', $permalink_args );

			return apply_filters( 'docpro_filters_profile_permalink', implode( '/', $permalink_args ) );
		}


		/**
		 * Return profile photo details
		 *
		 * @param string $return url | id | width | height | thumbnail | alt | title | description
		 *
		 * @return mixed|string
		 */
		function get_profile_photo( $return = 'url' ) {

			switch ( $this->type ) {
				case 'doctor' :
					$photo_args = empty( $this->profile_photo ) ? array() : $this->profile_photo;
					break;

				case 'clinic' :
					$photo_args = empty( $this->logo ) ? array() : $this->logo;
					break;

				default:
					$photo_args = array();
			}

			$profile_photo = docpro()->get_args_option( $return, '', $photo_args );;

			return $profile_photo;
		}


		/**
		 * Return avatar image url
		 *
		 * @return false|string
		 */
		function get_avatar_url() {

			if ( ! empty( $profile_photo = $this->get_profile_photo() ) ) {
				return $profile_photo;
			}

			return get_avatar_url( $this->ID );
		}


		/**
		 * Return user meta data
		 *
		 * @param string $meta_key
		 * @param string $default
		 * @param false $single
		 *
		 * @return false|mixed|void
		 */
		function get_meta( $meta_key = '', $default = '', $single = true ) {

			if ( empty( $meta_key ) ) {
				return false;
			}

			$meta_value = get_user_meta( $this->ID, $meta_key, $single );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'docpro_filters_get_user_meta', $meta_value, $meta_key, $this->ID, $default, $single );
		}


		/**
		 * will be extent to return dashboard navigation items
		 *
		 * @return array
		 */
		function get_dashboard_navigation() {
			return array();
		}

		/**
		 * Set primary data
		 */
		public function set_data() {
		}
	}
}
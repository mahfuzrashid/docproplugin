<?php
/**
 * Class Hooks
 */


if ( ! class_exists( 'DOCPRO_Hooks' ) ) {
	/**
	 * Class DOCPRO_Hooks
	 */
	class DOCPRO_Hooks {

		/**
		 * DOCPRO_Hooks constructor.
		 */
		function __construct() {

			if ( ! is_admin() ) {
				add_action( 'init', array( $this, 'ob_start' ) );
				add_action( 'wp_footer', array( $this, 'ob_end' ) );
			}

			add_action( 'init', array( $this, 'register_everything' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 9, 1 );
			add_filter( 'get_avatar_url', array( $this, 'change_profile_photo' ), 9, 2 );
			add_action( 'wp_ajax_handle_likebox', array( $this, 'handle_likebox_action' ) );
			add_action( 'wp_ajax_add_repeater_field', array( $this, 'add_repeater_field' ) );
			add_action( 'wp_ajax_add_education', array( $this, 'add_education' ) );
			add_action( 'wp_ajax_add_experience', array( $this, 'add_experience' ) );
			add_action( 'wp_ajax_add_service', array( $this, 'add_service' ) );
			add_action( 'woocommerce_checkout_order_created', array( $this, 'create_booking_on_complete' ) );
			add_filter( 'register_url', array( $this, 'update_registration_url' ) );
			add_filter( 'login_url', array( $this, 'update_login_url' ) );
			add_action( 'wp_ajax_handle_appointment_action', array( $this, 'handle_appointment_action' ) );
			add_action( 'wp_ajax_save_appointment_files', array( $this, 'save_appointment_files' ) );
			add_filter( 'account_lock_message', array( $this, 'update_account_lock_message' ) );
			add_action( 'wp_ajax_remove_favourite_item', array( $this, 'remove_favourite_item' ) );
		}

		/**
		 * Remove favourite item
		 *
		 * @return void
		 */
		function remove_favourite_item() {

			$entity_id = isset( $_POST['entity_id'] ) ? sanitize_text_field( $_POST['entity_id'] ) : '';

			if ( ! is_user_logged_in() || empty( $entity_id ) ) {
				wp_send_json_error();
			}

			$entity          = docpro_get_profile( '', $entity_id );
			$_followers      = $entity->get_meta( '_followers', array(), false );
			$current_user_id = get_current_user_id();

			if ( in_array( $current_user_id, $_followers ) ) {
				delete_user_meta( $entity_id, '_followers', $current_user_id );
				wp_send_json_success();
			}

			wp_send_json_error();
		}


		/**
		 * Update login error message
		 *
		 * @param $message
		 *
		 * @return string|string[]
		 */
		function update_account_lock_message( $message ) {
			return str_replace( 'Error:', esc_html__( 'Error: Please check your email inbox and verify.', 'docpro' ), $message );
		}


		function save_appointment_files() {
			$posted_data = wp_unslash( $_POST );
			$file_ids    = docpro()->get_args_option( 'file_ids', array(), $posted_data );
			$booking_id  = docpro()->get_args_option( 'booking_id', '', $posted_data );

			if ( empty( $booking_id ) ) {
				wp_send_json_error();
			}

			update_post_meta( $booking_id, '_files', $file_ids );
			wp_send_json_success( esc_html__( 'Saved', 'docpro' ) );
		}


		/**
		 * Handle appointment action
		 */
		function handle_appointment_action() {
			$posted_data = wp_unslash( $_POST );
			$to_do       = docpro()->get_args_option( 'to_do', '', $posted_data );
			$booking_id  = docpro()->get_args_option( 'booking_id', '', $posted_data );
			$status      = 'pending';

			if ( empty( $to_do ) || empty( $booking_id ) ) {
				wp_send_json_error();
			}

			if ( $to_do == 'accept' ) {
				$status = 'approved';
			} else if ( $to_do == 'cancel' ) {
				$status = 'cancelled';
			}

			update_post_meta( $booking_id, '_status', $status );
			wp_send_json_success( ucfirst( $status ) );
		}


		/**
		 * Update login url
		 *
		 * @return string|void
		 */
		function update_login_url() {
			return site_url( 'dashboard' );
		}


		/**
		 * Return registration page URL
		 *
		 * @param $url
		 *
		 * @return false|mixed|string|WP_Error
		 */
		function update_registration_url( $url ) {

			if ( ! empty( $reg_page_id = docpro()->get_option( 'docpro_reg_page' ) ) ) {
				$url = get_the_permalink( $reg_page_id );
			}

			return $url;
		}

		/**
		 * Create booking on complete order status
		 *
		 * @param WC_Order $order
		 */
		function create_booking_on_complete( WC_Order $order ) {

			$patient = $order->get_user();

			foreach ( $order->get_items() as $order_item ) {

				$product_id = docpro()->get_args_option( 'product_id', '', $order_item->get_data() );
				$service    = docpro()->get_meta( '_service', $product_id, array() );
				$doctor_id  = docpro()->get_meta( '_doctor_id', $product_id, '' );

				if ( ! is_array( $service ) || empty( $service ) || ! $order_item instanceof WC_Order_Item_Product ) {
					continue;
				}

				// adding patient role
				if ( ! $patient->has_cap( 'patient' ) ) {
					$patient->add_role( 'patient' );
				}

				$booking_args = array(
					'post_type'   => 'booking',
					'post_status' => 'publish',
					'meta_input'  => array(
						'_product_id'        => $product_id,
						'_doctor_id'         => $doctor_id,
						'_order_id'          => $order->get_id(),
						'_order_sub_total'   => $order_item->get_subtotal(),
						'_patient_id'        => $patient->ID,
						'_service_id'        => docpro()->get_args_option( 'id', '', $service ),
						'_service_title'     => docpro()->get_args_option( 'title', '', $service ),
						'_service_details'   => docpro()->get_args_option( 'details', '', $service ),
						'_service_price'     => docpro()->get_args_option( 'price', '', $service ),
						'_service_is_active' => docpro()->get_args_option( 'is_active', true, $service ),
						'_date'              => docpro()->get_meta( '_date', $product_id, '' ),
						'_time'              => docpro()->get_meta( '_time', $product_id, '' ),
						'_service'           => $service,
						'_status'            => 'pending',
					),
				);
				$booking_args = apply_filters( 'docpro_filters_booking_args', $booking_args, $order_id );


				/**
				 * Action before creating booking
				 */
				do_action( 'docpro_before_booking_created', $booking_args, $order_id );


				// inserting booking
				$booking_id = wp_insert_post( $booking_args );


				if ( $booking_id ) {
					/**
					 * Action after creating booking
					 */
					do_action( 'docpro_after_booking_created', $booking_args, $order_id );

					$order->update_status( 'completed' );
				}
			}
		}


		/**
		 * Return empty fields of service
		 */
		function add_service() {
			wp_send_json_success( docpro_get_service_fields() );
		}


		/**
		 * Return empty fields of experience
		 */
		function add_experience() {
			wp_send_json_success( docpro_get_experience_fields() );
		}


		/**
		 * Return empty fields of education
		 */
		function add_education() {
			wp_send_json_success( docpro_get_education_fields() );
		}


		/**
		 * Control repeater addition fields
		 */
		function add_repeater_field() {

			if ( ! empty( $callback = docpro()->get_args_option( 'callback', '', wp_unslash( $_POST ) ) ) ) {
				wp_send_json_success( call_user_func_array( $callback, array( array( 'unique_id' => $index ) ) ) );
			}

			wp_send_json_error();
		}


		/**
		 * Handle likebox action
		 */
		function handle_likebox_action() {

			$posted_data   = wp_unslash( $_POST );
			$action_target = docpro()->get_args_option( 'target', 'like', $posted_data );
			$entity_id     = docpro()->get_args_option( 'entity', '', $posted_data );

			if ( ! is_user_logged_in() || empty( $entity_id ) ) {
				wp_send_json_error( false );
			}

			$entity          = docpro_get_profile( '', $entity_id );
			$_followers      = $entity->get_meta( '_followers' );
			$current_user_id = get_current_user_id();

			if ( $action_target == 'like' && ! in_array( $current_user_id, $_followers ) ) {
				add_user_meta( $entity_id, '_followers', $current_user_id );
				wp_send_json_success( 'liked' );
			}

			if ( $action_target == 'unlike' && in_array( $current_user_id, $_followers ) ) {
				delete_user_meta( $entity_id, '_followers', $current_user_id );
				wp_send_json_success( 'unliked' );
			}

			wp_send_json_error( false );
		}


		/**
		 * Return profile
		 *
		 * @param $url
		 * @param $user_id
		 *
		 * @return mixed
		 */
		function change_profile_photo( $url, $user_id ) {

			if ( docpro_is_user( 'doctor', $user_id ) ) {
				$user = docpro_get_profile( 'doctor', $user_id );
			} else if ( docpro_is_user( 'patient', $user_id ) ) {
				$user = docpro_get_profile( 'patient', $user_id );
			} else if ( docpro_is_user( 'clinic', $user_id ) ) {
				$user = docpro_get_profile( 'clinic', $user_id );
			} else {
				$user = docpro_get_profile();
			}

			if ( ! empty( $profile_photo_url = $user->get_profile_photo() ) ) {


				return $profile_photo_url;
			}

			return $url;
		}


		/**
		 * Add custom query vars
		 *
		 * @param $query_vars
		 *
		 * @return mixed
		 */
		function add_query_vars( $query_vars ) {

			foreach ( docpro()->get_query_vars() as $query_var => $places ) {
				$query_vars[] = $query_var;
			}

			return $query_vars;
		}


		/**
		 * Register Post types, Taxes, Pages and Shortcodes
		 */
		function register_everything() {

			// Location role caps
			$location_caps = docpro()->get_location_capabilities();
			$role_doctor   = get_role( 'doctor' );
			$role_patient  = get_role( 'patient' );
			$role_clinic   = get_role( 'clinic' );
			$role_admin    = get_role( 'administrator' );

			foreach ( $location_caps as $capability ) {
				if ( $role_doctor ) {
					$role_doctor->add_cap( $capability );
				}

				if ( $role_clinic ) {
					$role_clinic->add_cap( $capability );
				}

				if ( $role_patient ) {
					$role_admin->add_cap( $capability );
				}
			}

			if ( $role_doctor ) {
				$role_doctor->add_cap( 'upload_files' );
				$role_doctor->add_cap( 'view_admin_dashboard' );
			}

			if ( $role_clinic ) {
				$role_clinic->add_cap( 'upload_files' );
				$role_clinic->add_cap( 'view_admin_dashboard' );
			}

			if ( $role_patient ) {
				$role_patient->add_cap( 'upload_files' );
				$role_patient->add_cap( 'view_admin_dashboard' );
			}

			// Check doctor profile page and restrict from editing
			if ( ! empty( $page_id = docpro()->get_args_option( 'post', '', wp_unslash( $_GET ) ) ) && docpro()->is_page( 'doctor', $page_id ) ) {
				add_action( 'edit_form_after_title', function () {
					docpro()->print_notice( esc_html__( 'This page currently set as doctor profile page. You can not make changes here!' ), 'warning inline', false );
				} );
				remove_post_type_support( 'page', 'editor' );
				remove_post_type_support( 'page', 'thumbnail' );
				remove_post_type_support( 'page', 'page-attributes' );
				remove_post_type_support( 'page', 'custom-fields' );
				remove_post_type_support( 'page', 'excerpt' );
				remove_post_type_support( 'page', 'author' );
				remove_post_type_support( 'page', 'comments' );
			}

			foreach ( docpro()->get_query_vars() as $query_var => $places ) {
				add_rewrite_endpoint( $query_var, $places );
			}

			docpro()->pbSettings = docpro()->PB_Settings( array(
				'add_in_menu'      => false,
				'plugin_name'      => esc_html__( 'Docpro', 'docpro' ),
				'required_plugins' => array(
					'woocommerce/woocommerce.php'             => esc_html( 'WooCommerce' ),
					'contact-form-7/wp-contact-form-7.php'    => esc_html( 'Contact Form 7' ),
					'oa-social-login/oa-social-login.php'     => esc_html( 'Social Login' ),
					'user-verification/user-verification.php' => esc_html( 'User Verification' ),
				),
			) );

			// Register post type - Location
			docpro()->pbSettings->register_post_type( 'location', apply_filters( 'docpro_filters_post_type_location', array(
				'singular'           => esc_html__( 'Location', 'docpro' ),
				'plural'             => esc_html__( 'All Location', 'docpro' ),
				'menu_icon'          => 'dashicons-location',
				'menu_position'      => 30,
				'supports'           => array( 'title' ),
				'capability_type'    => array( 'post', 'doctor' ),
				'capabilities'       => $location_caps,
				'public'             => false,
				'publicly_queryable' => false,
			) ) );

			// Register post type - Booking
			docpro()->pbSettings->register_post_type( 'booking', apply_filters( 'docpro_filters_post_type_booking', array(
				'singular'           => esc_html__( 'Booking', 'docpro' ),
				'plural'             => esc_html__( 'All Bookings', 'docpro' ),
				'supports'           => array( '' ),
				'public'             => false,
				'show_in_menu'       => false,
				'publicly_queryable' => false,
			) ) );


			// Add Settings Menu
			CSF::createOptions( 'docpro_settings', array(
				'framework_title'    => esc_html__( 'Docpro - Doctor Listing WordPress Plugin', 'docpro' ),
				'menu_title'         => esc_html__( 'Docpro', 'docpro' ),
				'menu_slug'          => 'docpro',
				'menu_icon'          => 'dashicons-plus-alt',
				'menu_position'      => 9,
				'theme'              => 'light',
				'show_reset_section' => false,
				'nav'                => 'inline',
				'data_type'          => 'unserialize',
				'footer_text'        => sprintf( esc_html__( 'Version: %s', 'docpro' ), DOCPRO_VERSION ),
				'footer_credit'      => esc_html__( 'Thank you for using Docpro', 'docpro' ),
			) );

			foreach ( docpro()->get_plugin_settings() as $section ) {
				CSF::createSection( 'docpro_settings', $section );
			}
		}

		/**
		 * Return Buffered Content
		 *
		 * @param $buffer
		 *
		 * @return mixed
		 */
		function ob_callback( $buffer ) {
			return $buffer;
		}


		/**
		 * Start of Output Buffer
		 */
		function ob_start() {
			ob_start( array( $this, 'ob_callback' ) );
		}


		/**
		 * End of Output Buffer
		 */
		function ob_end() {
			if ( ob_get_length() ) {
				ob_end_flush();
			}
		}
	}

	new DOCPRO_Hooks();
}
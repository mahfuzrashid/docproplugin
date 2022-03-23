<?php
/**
 * Plugin Name: Docpro
 * Plugin URI: https://docpro.com/
 * Description: Doctor website
 * Version: 1.0.1
 * Author: Docpro
 * Text Domain: docpro
 * Domain Path: /languages/
 * Author URI: https://docpro.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;
defined( 'DOCPRO_PLUGIN_URL' ) || define( 'DOCPRO_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'DOCPRO_PLUGIN_DIR' ) || define( 'DOCPRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'DOCPRO_PLUGIN_FILE' ) || define( 'DOCPRO_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'DOCPRO_VERSION' ) || define( 'DOCPRO_VERSION', '1.0.0' );

/**
 * @global DOCPRO_User_doctor $doctor Global Doctor object
 */
global $doctor;

/**
 * @global DOCPRO_User_patient $patient Global Patient object
 */
global $patient;

/**
 * @global DOCPRO_User_clinic $clinic Global Clinic object
 */
global $clinic;


/**
 * @global WP_User_Query $docpro_query
 */
global $docpro_query;


if ( ! class_exists( 'docproMain' ) ) {
	/**
	 * Class docproMain
	 */
	class docproMain {

		/**
		 * docproMain constructor.
		 */
		function __construct() {

			$this->load_scripts();
			$this->define_classes_functions();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );
		}


		/**
		 * On Plugin Deactivation
		 */
		function plugin_deactivation() {

			// Removing doctor role
			remove_role( 'doctor' );
			remove_role( 'patient' );
			remove_role( 'clinic' );

			// Removing doctor frontend profile page
			if ( ! empty( $page_id = get_option( 'docpro_page_profiles' ) ) ) {
				wp_delete_post( $page_id, true );
				delete_option( 'docpro_page_profiles' );
			}
		}


		function plugin_activation() {

			// Creating doctor user role
			add_role( 'doctor', esc_html__( 'Doctor', 'docpro' ), array( 'read' => true ) );
			add_role( 'patient', esc_html__( 'Patient', 'docpro' ), array( 'read' => true ) );
			add_role( 'clinic', esc_html__( 'Clinic', 'docpro' ), array( 'read' => true ) );


			// Creating doctor frontend profile page
			if ( empty( get_option( 'docpro_page_profiles' ) ) ) {
				$page_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => esc_html__( 'Profiles', 'docpro' ), 'post_content' => '[docpro-profiles]' ) );
				update_option( 'docpro_page_profiles', $page_id );
			}
		}


		/**
		 * Loading TextDomain
		 */
		function load_textdomain() {

			load_plugin_textdomain( 'docpro', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages/' );
		}


		/**
		 * Loading classes and functions
		 */
		function define_classes_functions() {

			require_once DOCPRO_PLUGIN_DIR . 'includes/codestar/classes/setup.class.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-csf-extended.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-pb-settings.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-functions.php';

			require_once DOCPRO_PLUGIN_DIR . 'includes/functions.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/functions-template.php';

			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-hooks.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-template-loader.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-meta-boxes.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-location.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-user-base.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-user-doctor.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-user-patient.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-user-clinic.php';
			require_once DOCPRO_PLUGIN_DIR . 'includes/classes/class-booking.php';
		}


		/**
		 * Return data that will pass on pluginObject
		 *
		 * @return array
		 */
		function localize_scripts_data() {
			return array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'confirmText' => esc_html__( 'Do you really wanted to proceed?', 'docpro' ),
				'saving'      => esc_html__( 'Saving...', 'docpro' ),
				'adding'      => esc_html__( 'Adding...', 'docpro' ),
				'working'     => esc_html__( 'Working...', 'docpro' ),
				'isAdmin'     => is_admin(),
			);
		}


		/**
		 * Loading scripts to backend
		 */
		function admin_scripts() {

			wp_enqueue_style( 'tooltip', DOCPRO_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'docpro', DOCPRO_PLUGIN_URL . 'assets/admin/css/style.css', array(), DOCPRO_VERSION );

			wp_enqueue_script( 'docpro', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ), DOCPRO_VERSION, true );
			wp_localize_script( 'docpro', 'docpro', $this->localize_scripts_data() );
		}


		/**
		 * Loading scripts to the frontend
		 */
		function front_scripts() {

			wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/fontawesome.min.css' );
			wp_enqueue_style( 'tooltip', DOCPRO_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'dataTables', DOCPRO_PLUGIN_URL . 'assets/front/css/jquery.dataTables.min.css' );
			wp_enqueue_style( 'docpro', DOCPRO_PLUGIN_URL . 'assets/front/css/style.css', array(), date( 'H:s' ) );

			wp_enqueue_script( 'dataTables', plugins_url( 'assets/front/js/jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ), DOCPRO_VERSION, true );
			wp_enqueue_script( 'docpro', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), date( 'H:s' ), true );
			wp_localize_script( 'docpro', 'docpro', $this->localize_scripts_data() );

			if ( ! empty( $gmap_api_key = docpro()->get_option( 'docpro_gmap_api' ) ) ) {
				wp_register_script( 'googlemap', sprintf( 'https://maps.googleapis.com/maps/api/js?key=%s&callback=initMap&libraries=&v=weekly', $gmap_api_key ) );
			}
		}


		/**
		 * Loading scripts
		 */
		function load_scripts() {

			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 99 );
		}
	}

	new docproMain();
}
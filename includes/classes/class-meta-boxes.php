<?php
/**
 * Metabox class
 *
 * @Author        Jaed Mosharraf
 * Copyright:    2015 Jaed Mosharraf
 */

if ( ! class_exists( 'DOCPRO_Meta_boxes' ) ) {
	/**
	 * Class DOCPRO_Meta_boxes
	 *
	 * @property string $prefix_admin;
	 * @property string $prefix_doctor;
	 * @property string $prefix_patient;
	 * @property string $prefix_clinic;
	 * @property string $prefix_location;
	 */
	class DOCPRO_Meta_boxes {

		/**
		 * DOCPRO_Meta_boxes constructor.
		 */
		public function __construct() {

			if ( ! class_exists( 'CSF' ) ) {
				return;
			}

			$this->prefix_admin    = esc_attr( 'admin_data' );
			$this->prefix_doctor   = esc_attr( 'doctor_data' );
			$this->prefix_patient  = esc_attr( 'patient_data' );
			$this->prefix_clinic   = esc_attr( 'clinic_data' );
			$this->prefix_location = esc_attr( 'location_data' );

			add_action( 'init', array( $this, 'add_meta_boxes' ), 9 );
			add_filter( 'manage_users_columns', array( $this, 'add_user_column' ) );
			add_filter( 'manage_users_custom_column', array( $this, 'add_user_column_content' ), 10, 3 );
		}


		/**
		 * Render frontend form for user meta data
		 *
		 * @param $user_id
		 * @param string $type
		 */
		function render_frontend_user_form_fields( $user_id, $type = 'doctor' ) {

			$meta_fields = $this->get_meta_fields( $type );

			foreach ( $meta_fields as $index => $field ) {

				$meta  = get_user_meta( $user_id, $field['id'] );
				$value = ( isset( $meta[0] ) ) ? $meta[0] : null;

				if ( $field['id'] == '_locations' && is_array( $value ) ) {
					$value = array_unique( array_filter( $value ) );
				}

				$meta_fields[ $index ]['value'] = $value;
			}

			CSF_Extended::createFrontendFormFields( apply_filters( 'docpro_user_meta_fields', $meta_fields, $user_id ) );
		}


		/**
		 * Render custom column content
		 *
		 * @param $output
		 * @param $column_name
		 * @param $user_id
		 *
		 * @return mixed
		 */
		function add_user_column_content( $output, $column_name, $user_id ) {

			$user = docpro_get_profile( '', $user_id );

			if ( $column_name == '_reviews' ) {
				return sprintf( '%s<div class="row-actions"><span class="edit"><a href="%s">%s</a></div>',
					docpro_render_rating( $user->get_average_review_rating(), array(), false ),
					sprintf( '%s&display=review', get_edit_user_link( $user_id ) ),
					esc_html__( 'Edit', 'docpro' )
				);
			}

			return $output;
		}


		/**
		 * Add custom column to users list page
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		function add_user_column( $columns ) {

			$columns['_reviews'] = esc_html__( 'Reviews', 'docpro' );

			return $columns;
		}


		/**
		 * Register doctor meta data boxes
		 */
		function add_meta_boxes() {

			$user_id      = docpro()->get_args_option( 'user_id', '', wp_unslash( $_GET ) );
			$user_id      = empty( $user_id ) ? docpro()->get_args_option( 'user_id', get_current_user_id(), wp_unslash( $_POST ) ) : $user_id;
			$user_viewing = docpro_get_profile( '', $user_id );


			// Adding admin data box
			if ( current_user_can( 'manage_options' ) ) {
				CSF::createProfileOptions( $this->prefix_admin, array( 'data_type' => 'unserialize' ) );
				CSF::createSection( $this->prefix_admin, array(
					'title'  => esc_html__( 'Docpro - Administrator Data', 'docpro' ),
					'fields' => $this->get_meta_fields( 'admin' )
				) );
			}

			// Adding doctor data box
			if ( current_user_can( 'doctor' ) || ( current_user_can( 'manage_options' ) && $user_viewing->has_cap( 'doctor' ) ) ) {
				CSF::createProfileOptions( $this->prefix_doctor, array( 'data_type' => 'unserialize' ) );
				CSF::createSection( $this->prefix_doctor, array(
					'title'  => esc_html__( 'Docpro - Doctor Data', 'docpro' ),
					'fields' => $this->get_meta_fields( 'doctor' )
				) );
			}


			// Adding patient data box
			if ( $user_viewing->get_role() == 'patient' || ( current_user_can( 'manage_options' ) && $user_viewing->has_cap( 'patient' ) ) ) {
				CSF::createProfileOptions( $this->prefix_patient, array( 'data_type' => 'unserialize' ) );
				CSF::createSection( $this->prefix_patient, array(
					'title'  => esc_html__( 'Docpro - Patient Data', 'docpro' ),
					'fields' => $this->get_meta_fields( 'patient' )
				) );
			}


			// Adding clinic data box
			if ( $user_viewing->get_role() == 'clinic' || ( current_user_can( 'manage_options' ) && $user_viewing->has_cap( 'clinic' ) ) ) {
				CSF::createProfileOptions( $this->prefix_clinic, array( 'data_type' => 'unserialize' ) );
				CSF::createSection( $this->prefix_clinic, array(
					'title'  => esc_html__( 'Docpro - Clinic Data', 'docpro' ),
					'fields' => $this->get_meta_fields( 'clinic' )
				) );
			}


			// Adding location meta box
			CSF::createMetabox( $this->prefix_location, array(
				'title'     => esc_html__( 'Location Data', 'docpro' ),
				'post_type' => 'location',
				'data_type' => 'unserialize',
			) );
			CSF::createSection( $this->prefix_location, array( 'fields' => $this->get_meta_fields( 'location' ) ) );
		}


		/**
		 * Return meta fields for different entities
		 *
		 * @param string $fields_for
		 *
		 * @return mixed|void
		 */
		function get_meta_fields( $fields_for = '' ) {

			$fields_for = empty( $fields_for ) ? '' : $fields_for;
			$locations  = array();
			foreach ( get_posts( 'post_type=location&posts_per_page=-1&post_status=publish&fields=ids' ) as $location_id ) {
				$locations[ $location_id ] = get_the_title( $location_id );
			}

			// Admin fields
			$meta_fields['admin'] = array(
				array(
					'id'      => '_is_verified',
					'type'    => 'checkbox',
					'title'   => esc_html__( 'Is Verified?', 'docpro' ),
					'label'   => esc_html__( 'Verified / Non Verified', 'docpro' ),
					'default' => false,
				),
				array(
					'id'           => '_reviews',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Reviews', 'docpro' ),
					'button_title' => esc_html__( 'Add Review', 'docpro' ),
					'class'        => 'docpro-review-section',
					'fields'       => array(
						array(
							'id'          => 'star',
							'type'        => 'number',
							'title'       => esc_html__( 'Star', 'docpro' ),
							'placeholder' => esc_html__( '5', 'docpro' ),
						),
						array(
							'id'          => 'title',
							'type'        => 'text',
							'title'       => esc_html__( 'Title', 'docpro' ),
							'placeholder' => esc_html__( 'Review title', 'docpro' ),
						),
						array(
							'id'          => 'message',
							'type'        => 'text',
							'title'       => esc_html__( 'Message', 'docpro' ),
							'placeholder' => esc_html__( 'Review text', 'docpro' ),
						),
						array(
							'id'    => 'reviewer',
							'type'  => 'number',
							'title' => esc_html__( 'Reviewer', 'docpro' ),
						),
						array(
							'id'    => 'datetime',
							'type'  => 'text',
							'title' => esc_html__( 'Date time', 'docpro' ),
						),

					),
				)
			);

			// Doctor fields
			$meta_fields['doctor'] = array(
				array(
					'id'    => '_profile_photo',
					'type'  => 'media',
					'title' => esc_html__( 'Profile Photo', 'docpro' ),
					'url'   => false,
				),
				array(
					'id'          => '_department',
					'type'        => 'select',
					'title'       => esc_html__( 'Department', 'docpro' ),
					'placeholder' => esc_html__( 'Select an option', 'docpro' ),
					'desc'        => esc_html__( 'Select your department', 'docpro' ),
					'options'     => docpro()->get_departments(),
					'multiple'    => true,
					'chosen'      => true,
					'settings'    => array(
						'min_length' => 2,
						'width'      => '50%',
					),
				),
				array(
					'id'           => '_specialities',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Specialities', 'docpro' ),
					'button_title' => esc_html__( 'Add Speciality', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'name',
							'type'        => 'text',
							'title'       => esc_html__( 'Name', 'docpro' ),
							'placeholder' => esc_html__( 'Cardiology', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_educations',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Educational Background', 'docpro' ),
					'button_title' => esc_html__( 'Add Education', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'degree',
							'type'        => 'text',
							'title'       => esc_html__( 'Degree', 'docpro' ),
							'placeholder' => esc_html__( 'MBBS', 'docpro' ),
						),
						array(
							'id'          => 'institute',
							'type'        => 'text',
							'title'       => esc_html__( 'Institute', 'docpro' ),
							'placeholder' => esc_html__( 'Dhaka Medical College and Hospital', 'docpro' ),
						),
						array(
							'id'          => 'year',
							'type'        => 'text',
							'title'       => esc_html__( 'Completed in', 'docpro' ),
							'placeholder' => esc_html__( '2008', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_services',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Services', 'docpro' ),
					'button_title' => esc_html__( 'Add Service', 'docpro' ),
					'fields'       => array(
						array(
							'id'      => 'id',
							'type'    => 'number',
//							'class'   => 'disabled',
							'title'   => esc_html__( 'Service ID', 'docpro' ),
							'default' => rand( 10000, 99999 ),
						),
						array(
							'id'          => 'title',
							'type'        => 'text',
							'title'       => esc_html__( 'Service Title', 'docpro' ),
							'placeholder' => esc_html__( 'New patient visit', 'docpro' ),
						),
						array(
							'id'          => 'details',
							'type'        => 'text',
							'title'       => esc_html__( 'Details', 'docpro' ),
							'placeholder' => esc_html__( 'General visit of new patient', 'docpro' ),
						),
						array(
							'id'          => 'price',
							'type'        => 'number',
							'title'       => esc_html__( 'Price', 'docpro' ),
							'placeholder' => esc_html__( '700', 'docpro' ),
						),
						array(
							'id'    => 'is_active',
							'type'  => 'checkbox',
							'title' => esc_html__( 'Enable/Disable', 'docpro' ),
							'label' => esc_html__( 'Enable of disable this service from taking appointment', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_awards',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Awards', 'docpro' ),
					'button_title' => esc_html__( 'Add Award', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'name',
							'type'        => 'text',
							'title'       => esc_html__( 'Award name', 'docpro' ),
							'placeholder' => esc_html__( 'Mother Teresa Award', 'docpro' ),
						),
						array(
							'id'          => 'organisation',
							'type'        => 'text',
							'title'       => esc_html__( 'Given by', 'docpro' ),
							'placeholder' => esc_html__( 'Govt of Bangladesh Medical Board', 'docpro' ),
						),
						array(
							'id'          => 'year',
							'type'        => 'number',
							'title'       => esc_html__( 'Year', 'docpro' ),
							'placeholder' => esc_html__( '2018', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_experiences',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Experiences', 'docpro' ),
					'button_title' => esc_html__( 'Add Experience', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'institution',
							'type'        => 'text',
							'title'       => esc_html__( 'Institution name', 'docpro' ),
							'placeholder' => esc_html__( 'IBN Sina Medical College and Hospital', 'docpro' ),
						),
						array(
							'id'          => 'position',
							'type'        => 'text',
							'title'       => esc_html__( 'Position', 'docpro' ),
							'placeholder' => esc_html__( 'Lecturer', 'docpro' ),
						),
						array(
							'id'          => 'department',
							'type'        => 'text',
							'title'       => esc_html__( 'Department', 'docpro' ),
							'placeholder' => esc_html__( 'Cardiology', 'docpro' ),
						),
						array(
							'id'          => 'started',
							'type'        => 'date',
							'title'       => esc_html__( 'Started at', 'docpro' ),
							'placeholder' => esc_html__( '01-06-2017', 'docpro' ),
							'settings'    => array(
								'dateFormat' => esc_attr( 'dd-mm-yy' ),
							),
						),
						array(
							'id'          => 'end',
							'type'        => 'date',
							'title'       => esc_html__( 'Completed at', 'docpro' ),
							'placeholder' => esc_html__( '31-12-2019', 'docpro' ),
							'settings'    => array(
								'dateFormat' => esc_attr( 'dd-mm-yy' ),
							),
							'desc'        => esc_html__( 'Leave empty if you are working here currently', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_skills',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Skills', 'docpro' ),
					'button_title' => esc_html__( 'Add Skill', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'name',
							'type'        => 'text',
							'title'       => esc_html__( 'Skill name', 'docpro' ),
							'placeholder' => esc_html__( 'Proficient in assisting all Gynecology & Obstetrics Surgeries', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'          => '_locations',
					'type'        => 'select',
					'title'       => esc_html__( 'Chamber Location', 'docpro' ),
					'placeholder' => esc_html__( 'Select an option', 'docpro' ),
					'desc'        => esc_html__( 'The first one will be set as primary location. You can drag the selected locations.', 'docpro' ),
					'options'     => $locations,
					'multiple'    => true,
					'sortable'    => true,
					'chosen'      => true,
					'settings'    => array(
						'min_length' => 2,
						'width'      => '50%',
					),
				),
				array(
					'id'          => '_phone',
					'type'        => 'text',
					'title'       => esc_html__( 'Phone Number', 'docpro' ),
					'placeholder' => esc_html__( '+191256364', 'docpro' ),
				),
				array(
					'id'      => '_availability',
					'type'    => 'checkbox',
					'title'   => esc_html__( 'Availability', 'docpro' ),
					'label'   => esc_html__( 'Available / Not available', 'docpro' ),
					'default' => true,
				),
				array(
					'id'          => '_visiting_time',
					'type'        => 'text',
					'title'       => esc_html__( 'Visiting Time', 'docpro' ),
					'placeholder' => esc_html__( 'Monday to Friday : 09:00 AM - 05:00 PM', 'docpro' ),
				),
			);

			// Patient fields
			$meta_fields['patient'] = array(
				array(
					'id'    => '_profile_photo',
					'type'  => 'media',
					'title' => esc_html__( 'Profile Photo', 'docpro' ),
					'url'   => false,
				),
				array(
					'id'    => '_dob',
					'type'  => 'date',
					'title' => esc_html__( 'Date of birth', 'docpro' ),
				),
				array(
					'id'      => '_gender',
					'type'    => 'select',
					'title'   => esc_html__( 'Gender', 'docpro' ),
					'options' => array(
						'male'   => esc_html__( 'Male', 'docpro' ),
						'female' => esc_html__( 'Female', 'docpro' ),
						'others' => esc_html__( 'Others', 'docpro' ),
					),
				),
				array(
					'id'          => '_age',
					'type'        => 'number',
					'title'       => esc_html__( 'Age', 'docpro' ),
					'placeholder' => esc_html__( '32', 'docpro' ),
				),
				array(
					'id'          => '_phone',
					'type'        => 'text',
					'title'       => esc_html__( 'Phone Number', 'docpro' ),
					'placeholder' => esc_html__( '+191256364', 'docpro' ),
				),
				array(
					'id'    => '_blood_group',
					'type'  => 'text',
					'title' => esc_html__( 'Blood Group', 'docpro' ),
				),
				array(
					'id'      => '_marital_status',
					'type'    => 'text',
					'title'   => esc_html__( 'Marital Status', 'docpro' ),
					'options' => array(
						'married' => esc_html__( 'Married', 'docpro' ),
						'single'  => esc_html__( 'Single', 'docpro' ),
					),
				),
				array(
					'id'          => '_note',
					'type'        => 'text',
					'title'       => esc_html__( 'Note to Doctor', 'docpro' ),
					'placeholder' => esc_html__( 'Write some special note to Doctor', 'docpro' ),
				),
				array(
					'id'          => '_address',
					'type'        => 'textarea',
					'title'       => esc_html__( 'Full Address', 'docpro' ),
					'placeholder' => esc_html__( '22/8 Road 6, Park Street', 'docpro' ),
				),
				array(
					'id'           => '_social_profiles',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Social Profiles', 'docpro' ),
					'button_title' => esc_html__( 'Add Social', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'platform',
							'type'        => 'text',
							'title'       => esc_html__( 'Platform Name', 'docpro' ),
							'placeholder' => esc_html__( 'Facebook', 'docpro' ),
						),
						array(
							'id'          => 'url',
							'type'        => 'text',
							'title'       => esc_html__( 'Profile URL', 'docpro' ),
							'placeholder' => esc_url( 'https://facebook.com/john.doe' ),
						),
					),
					'default'      => array( '' ),
				),
			);

			// Clinic fields
			$meta_fields['clinic'] = array(
				array(
					'id'    => '_logo',
					'type'  => 'media',
					'title' => esc_html__( 'Logo', 'docpro' ),
					'url'   => false,
				),
				array(
					'id'          => '_slogan',
					'type'        => 'text',
					'title'       => esc_html__( 'Slogan', 'docpro' ),
					'placeholder' => esc_html__( 'Happy to serve', 'docpro' ),
				),
				array(
					'id'          => '_phone',
					'type'        => 'text',
					'title'       => esc_html__( 'Phone Number', 'docpro' ),
					'placeholder' => esc_html__( '+191256364', 'docpro' ),
				),
				array(
					'id'          => '_about',
					'type'        => 'textarea',
					'title'       => esc_html__( 'About', 'docpro' ),
					'placeholder' => esc_html__( 'Write details about this clinic', 'docpro' ),
				),
				array(
					'id'           => '_specifications',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Specifications', 'docpro' ),
					'button_title' => esc_html__( 'Add Specification', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'name',
							'type'        => 'text',
							'title'       => esc_html__( 'Name', 'docpro' ),
							'placeholder' => esc_html__( 'Cardiology', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_services',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Services', 'docpro' ),
					'button_title' => esc_html__( 'Add Service', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'title',
							'type'        => 'text',
							'title'       => esc_html__( 'Service Title', 'docpro' ),
							'placeholder' => esc_html__( 'New patient visit', 'docpro' ),
						),
						array(
							'id'          => 'details',
							'type'        => 'text',
							'title'       => esc_html__( 'Details', 'docpro' ),
							'placeholder' => esc_html__( 'General visit of new patient', 'docpro' ),
						),
						array(
							'id'          => 'price',
							'type'        => 'number',
							'title'       => esc_html__( 'Price', 'docpro' ),
							'placeholder' => esc_html__( '700', 'docpro' ),
						),
						array(
							'id'    => 'is_active',
							'type'  => 'checkbox',
							'title' => esc_html__( 'Enable/Disable', 'docpro' ),
							'label' => esc_html__( 'Enable of disable this service from taking appointment', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'           => '_awards',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Awards', 'docpro' ),
					'button_title' => esc_html__( 'Add Award', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'name',
							'type'        => 'text',
							'title'       => esc_html__( 'Award name', 'docpro' ),
							'placeholder' => esc_html__( 'Mother Teresa Award', 'docpro' ),
						),
						array(
							'id'          => 'organisation',
							'type'        => 'text',
							'title'       => esc_html__( 'Given by', 'docpro' ),
							'placeholder' => esc_html__( 'Govt of Bangladesh Medical Board', 'docpro' ),
						),
						array(
							'id'          => 'year',
							'type'        => 'number',
							'title'       => esc_html__( 'Year', 'docpro' ),
							'placeholder' => esc_html__( '2018', 'docpro' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'    => '_gallery',
					'type'  => 'gallery',
					'title' => esc_html__( 'Gallery Images', 'docpro' ),
				),
				array(
					'id'          => '_doctors',
					'type'        => 'select',
					'title'       => esc_html__( 'Onboard Doctors', 'docpro' ),
					'placeholder' => esc_html__( 'Select a Doctor', 'docpro' ),
					'desc'        => esc_html__( 'You can drag the selected doctor to sort.', 'docpro' ),
					'options'     => docpro()->get_doctors_list(),
					'multiple'    => true,
					'sortable'    => true,
					'chosen'      => true,
					'settings'    => array(
						'min_length' => 2,
						'width'      => '50%',
					),
				),
				array(
					'id'          => '_locations',
					'type'        => 'select',
					'title'       => esc_html__( 'Chamber Location', 'docpro' ),
					'placeholder' => esc_html__( 'Select an option', 'docpro' ),
					'desc'        => esc_html__( 'The first one will be set as primary location. You can drag the selected locations.', 'docpro' ),
					'options'     => $locations,
					'multiple'    => true,
					'sortable'    => true,
					'chosen'      => true,
					'settings'    => array(
						'min_length' => 2,
						'width'      => '50%',
					),
				),
				array(
					'id'          => '_fax',
					'type'        => 'text',
					'title'       => esc_html__( 'FAX', 'docpro' ),
					'placeholder' => esc_html__( '+191256364', 'docpro' ),
				),
				array(
					'id'           => '_social_profiles',
					'type'         => 'repeater',
					'title'        => esc_html__( 'Social Profiles', 'docpro' ),
					'button_title' => esc_html__( 'Add Social', 'docpro' ),
					'fields'       => array(
						array(
							'id'          => 'platform',
							'type'        => 'text',
							'title'       => esc_html__( 'Platform Name', 'docpro' ),
							'placeholder' => esc_html__( 'Facebook', 'docpro' ),
						),
						array(
							'id'    => 'icon',
							'type'  => 'icon',
							'title' => esc_html__( 'Platform Icon', 'docpro' ),
						),
						array(
							'id'          => 'url',
							'type'        => 'text',
							'title'       => esc_html__( 'Profile URL', 'docpro' ),
							'placeholder' => esc_url( 'https://facebook.com/john.doe' ),
						),
					),
					'default'      => array( '' ),
				),
				array(
					'id'      => '_availability',
					'type'    => 'checkbox',
					'title'   => esc_html__( 'Availability', 'docpro' ),
					'label'   => esc_html__( 'Available / Not available', 'docpro' ),
					'default' => true,
				),
			);

			// Location fields
			$meta_fields['location'] = array(
				array(
					'id'          => '_lat',
					'type'        => 'text',
					'title'       => esc_html__( 'Latitude', 'docpro' ),
					'placeholder' => esc_attr( '25.740580' ),
				),
				array(
					'id'          => '_long',
					'type'        => 'text',
					'title'       => esc_html__( 'Longitude', 'docpro' ),
					'placeholder' => esc_attr( '89.261139' ),
				),
				array(
					'id'          => '_street',
					'type'        => 'text',
					'title'       => esc_html__( 'Street Address', 'docpro' ),
					'placeholder' => esc_attr( '14/5 Road - 2' ),
				),
				array(
					'id'          => '_city',
					'type'        => 'text',
					'title'       => esc_html__( 'City', 'docpro' ),
					'placeholder' => esc_attr( 'Rangpur' ),
				),
				array(
					'id'          => '_postcode',
					'type'        => 'text',
					'title'       => esc_html__( 'Postcode', 'docpro' ),
					'placeholder' => esc_attr( '5405' ),
				),
				array(
					'id'          => '_phone',
					'type'        => 'text',
					'title'       => esc_html__( 'Phone', 'docpro' ),
					'placeholder' => esc_attr( '911 444 6666' ),
				),
				array(
					'id'          => '_country_state',
					'type'        => 'select',
					'title'       => esc_html__( 'Country and State', 'docpro' ),
					'desc'        => esc_html__( 'Just select your state or city, system will automatically set your country.', 'docpro' ),
					'options'     => docpro()->get_all_countries_states(),
					'placeholder' => esc_html__( 'Select your location', 'docpro' ),
					'chosen'      => true,
					'settings'    => array(
						'width' => '50%',
					),
				),
			);

			$return_fields = docpro()->get_args_option( $fields_for, array(), apply_filters( 'docpro_filters_meta_fields', $meta_fields ) );

			return apply_filters( 'docpro_filters_meta_fields_' . $fields_for, $return_fields, $fields_for );
		}


		/**
		 * Return field ids
		 *
		 * @param string $fields_for
		 *
		 * @return mixed|void
		 */
		function get_field_ids( $fields_for = 'doctor' ) {

			$all_fields = array_map( function ( $field ) {
				return docpro()->get_args_option( 'id', '', $field );
			}, $this->get_meta_fields( $fields_for ) );

			return apply_filters( 'docpro_filters_get_field_ids_for_' . $fields_for, array_filter( $all_fields ) );
		}
	}

	docpro()->metaBoxes = new DOCPRO_Meta_boxes();
}
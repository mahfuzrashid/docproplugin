<?php
/**
 * Booking Class
 */

if ( ! class_exists( 'DOCPRO_Booking' ) ) {
	/**
	 * Class DOCPRO_Booking
	 *
	 * @property int $id;
	 * @property int $product_id;
	 * @property int $doctor_id;
	 * @property int $order_id;
	 * @property string $order_sub_total;
	 * @property int $patient_id;
	 * @property int $service_id;
	 * @property string $service_title;
	 * @property string $service_details;
	 * @property string $service_price;
	 * @property string $service_is_active;
	 * @property string $date;
	 * @property string $time;
	 * @property array $service;
	 * @property string $status;
	 * @property DOCPRO_User_patient $patient;
	 * @property DOCPRO_User_doctor $doctor;
	 */
	class DOCPRO_Booking {

		public $id;

		/**
		 * DOCPRO_Booking constructor.
		 *
		 * @param $booking_id
		 */
		function __construct( $booking_id ) {
			$this->set_data( $booking_id );
		}


		/**
		 * Return formatted service price
		 *
		 * @return string
		 */
		function get_service_price_formatted() {

			if ( function_exists( 'WC' ) ) {
				return wc_price( $this->service_price );
			}

			return sprintf( '$%s', $this->service_price );
		}


		/**
		 * Return files
		 *
		 * @return array|string|void
		 */
		function get_files() {
			$files = docpro()->get_meta( '_files', $this->id );

			return empty( $files ) || ! is_array( $files ) ? array() : $files;
		}


		/**
		 * Return booking payment details
		 *
		 * @param string $return
		 *
		 * @return bool|string|WC_Order|WC_Order_Refund
		 */
		function get_payment_details( $return = '' ) {

			$booking_order = wc_get_order( $this->order_id );

			switch ( $return ) {
				case 'status':
					return ucfirst( $booking_order->get_status() );

				case 'method':
					return $booking_order->get_payment_method_title();

				case 'amount':
					return $this->order_sub_total;
			}

			return '';
		}


		/**
		 * Return booking status
		 *
		 * @param bool $raw
		 *
		 * @return mixed|string|void
		 */
		function get_status( $raw = false ) {
			$status = docpro()->get_meta( '_status', $this->id, 'pending' );

			if ( $raw ) {
				return $status;
			}

			return ucfirst( $status );
		}


		/**
		 * Return booking date time
		 *
		 * @return string
		 */
		function get_date_time() {
			return $this->date . ' ' . $this->time;
		}


		/**
		 * Return patient
		 *
		 * @return DOCPRO_User_base|DOCPRO_User_clinic|DOCPRO_User_doctor|DOCPRO_User_patient
		 */
		private function get_doctor() {
			return docpro_get_profile( 'doctor', $this->doctor_id );
		}


		/**
		 * Return patient
		 *
		 * @return DOCPRO_User_base|DOCPRO_User_clinic|DOCPRO_User_doctor|DOCPRO_User_patient
		 */
		private function get_patient() {
			return docpro_get_profile( 'patient', $this->patient_id );
		}


		/**
		 * Set primary data for a doctor
		 *
		 * @param $booking_id
		 */
		function set_data( $booking_id ) {

			$this->id = $booking_id;

			foreach ( $this->get_meta_fields() as $field_id ) {
				$this->{ltrim( $field_id, '_' )} = docpro()->get_meta( $field_id, $this->id );
			}

			$this->patient = $this->get_patient();
			$this->doctor  = $this->get_doctor();
		}


		/**
		 * Set meta data
		 */
		function get_meta_fields() {
			return array(
				'_product_id',
				'_doctor_id',
				'_order_id',
				'_order_sub_total',
				'_patient_id',
				'_service_id',
				'_service_title',
				'_service_details',
				'_service_price',
				'_service_is_active',
				'_date',
				'_time',
				'_service',
				'_status',
			);
		}
	}
}
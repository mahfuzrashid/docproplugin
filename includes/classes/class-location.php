<?php
/**
 * Class Docpro Location
 *
 * @property int $id
 * @property WP_Post $post
 * @property string $lat
 * @property string $long
 * @property string $street
 * @property string $city
 * @property string $postcode
 * @property string $state
 * @property string $country
 * @property string $phone
 * @property string $country_state
 */


final class DOCPRO_Location {

	/**
	 * DOCPRO_Location constructor.
	 *
	 * @param int $id
	 */
	function __construct( $id = 0 ) {
		$this->init( $id );
	}


	/**
	 * Return formatted address of this location
	 *
	 * @param string $format
	 *
	 * @return mixed|void
	 */
	function get_formatted_address( $format = '' ) {

		$search_items = array( '{street}', '{city}', '{postcode}', '{state}', '{country}' );
		$replace_with = array( $this->street, $this->city, $this->postcode, $this->state, $this->country );
		$address      = str_replace( $search_items, $replace_with, $this->get_address_format( $format ) );

		return apply_filters( 'docpro_filters_formatted_address', $address );
	}


	/**
	 * Return address format
	 *
	 * @param string $format
	 *
	 * @return mixed|void
	 */
	function get_address_format( $format = '' ) {
		$format = empty( $format ) ? '{street}, {city}' : $format;

		return apply_filters( 'docpro_filters_address_format', $format, $this->id, $this->post );
	}


	/**
	 * Return country label by country code
	 *
	 * @param string $country
	 *
	 * @return mixed|void
	 */
	function get_country_label( $country = '' ) {
		$all_countries = docpro()->get_countries();
		$country_label = isset( $all_countries[ $country ] ) ? $all_countries[ $country ] : '';

		return apply_filters( 'docpro_filters_country_label', $country_label );
	}


	/**
	 * Return state label by state and country code
	 *
	 * @param string $state
	 * @param string $country
	 *
	 * @return mixed|void
	 */
	function get_state_label( $state = '', $country = '' ) {

		$all_states  = docpro()->get_states();
		$state_label = isset( $all_states[ $country ][ $state ] ) ? $all_states[ $country ][ $state ] : '';

		return apply_filters( 'docpro_filters_state_label', $state_label, $state, $country );
	}


	/**
	 * Init data for this location
	 *
	 * @param $post_id
	 */
	function init( $post_id ) {
		$this->id   = ! $post_id || empty( $post_id ) || $post_id === 0 ? get_the_ID() : $post_id;
		$this->post = get_post( $this->id );

		foreach ( docpro()->metaBoxes->get_field_ids( 'location' ) as $field_id ) {
			$this->{ltrim( $field_id, '_' )} = docpro()->get_meta( $field_id, $this->id );
		}

		$country_state = explode( '#', $this->country_state );
		$country       = isset( $country_state[0] ) ? $country_state[0] : '';
		$state         = isset( $country_state[1] ) ? $country_state[1] : '';
		$this->country = $this->get_country_label( $country );
		$this->state   = $this->get_state_label( $state, $country );
	}
}
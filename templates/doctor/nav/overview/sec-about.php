<?php
/**
 * Doctor Details - Nav Item Overview Section - About
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;
?>
<h3><?php esc_html_e( 'About', 'docpro' ); ?></h3>

<p><?php echo esc_html( $doctor->description ); ?></p>
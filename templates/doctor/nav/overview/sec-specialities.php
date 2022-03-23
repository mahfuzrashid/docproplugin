<?php
/**
 * Doctor Details - Nav Item Overview Section - Specialities
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;
?>
    <h3><?php esc_html_e( 'Specialities', 'docpro' ); ?></h3>

    <p><?php echo apply_filters( 'docpro_filters_text_overview_specialities', sprintf( wp_kses( '<strong>%s</strong> has speciality in the below fields', 'docpro' ), $doctor->display_name ) ); ?></p>

<?php if ( ! empty( $specialities = docpro_array_map( 'name', $doctor->specialities ) ) ) : ?>
    <ul class="treatments-list list clearfix">
		<?php printf( '<li>%s</li>', implode( '</li><li>', $specialities ) ); ?>
    </ul>
<?php endif; ?>
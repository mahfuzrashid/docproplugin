<?php
/**
 * Clinic Details - Nav Item Overview Section - Specifications
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>
    <h3><?php esc_html_e( 'Specifications', 'docpro' ); ?></h3>

    <p><?php echo apply_filters( 'docpro_filters_text_overview_specifications', sprintf( wp_kses( '<strong>%s</strong> has speciality in the below fields', 'docpro' ), $clinic->display_name ) ); ?></p>

<?php if ( ! empty( $specifications = docpro_array_map( 'name', $clinic->specifications ) ) ) : ?>
    <ul class="treatments-list list clearfix">
		<?php printf( '<li>%s</li>', implode( '</li><li>', $specifications ) ); ?>
    </ul>
<?php endif; ?>
<?php
/**
 * Doctor Details - Nav Item Overview Section - Educations
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>
    <h3><?php esc_html_e( 'Educational Background', 'docpro' ); ?></h3>

<?php if ( ! empty( $doctor->educations ) ) : ?>

    <ul class="list clearfix">
		<?php foreach ( $doctor->educations as $education ) : ?>
			<?php echo wp_kses_post( sprintf( '<li>%s <span>at %s in %s</span></li>',
				docpro()->get_args_option( 'degree', '', $education ),
				docpro()->get_args_option( 'institute', '', $education ),
				docpro()->get_args_option( 'year', '', $education ) ) ); ?>
		<?php endforeach; ?>
    </ul>

<?php endif; ?>
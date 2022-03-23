<?php
/**
 * Clinic Details - Nav Item Overview Section - Awards
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>
<div class="award-box">
    <h3><?php esc_html_e( 'Awards', 'docpro' ); ?></h3>

	<?php if ( ! empty( $clinic->awards ) ) : ?>

        <ul class="list clearfix">
			<?php foreach ( $clinic->awards as $award ) : ?>
				<?php printf( '<li>%s <span>%s</span></li>',
					sprintf( esc_html__( 'Award (%s) win by %s', 'docpro' ),
						docpro()->get_args_option( 'name', '', $award ),
						docpro()->get_args_option( 'organisation', '', $award )
					),
					docpro()->get_args_option( 'year', '', $award ) ); ?>
			<?php endforeach; ?>
        </ul>

	<?php endif; ?>
</div>


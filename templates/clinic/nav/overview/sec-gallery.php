<?php
/**
 * Clinic Details - Nav Item Overview Section - Awards
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>
<div class="image-gallery">
    <h3><?php esc_html_e( 'Gallery', 'docpro' ); ?></h3>

	<?php if ( ! empty( $clinic->gallery ) ) : ?>

            <ul class="image-list clearfix">
	            <?php foreach ( $clinic->gallery as $attachment_id ) : ?>
		            <?php
                    printf( '<li><figure class="image"><img src="%s" alt="%s"></figure></li>',
                        wp_get_attachment_image_url($attachment_id), get_the_title( $attachment_id )
                    );
                    ?>
	            <?php endforeach; ?>
            </ul>

	<?php endif; ?>
</div>


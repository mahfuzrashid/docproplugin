<?php
/**
 * Dashboard - Doctor Reviews
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>

<div class="review-list">
    <div class="title-box clearfix">
        <div class="text pull-left"><h3><?php esc_html_e( 'Reviews List', 'docpro' ); ?></h3></div>
    </div>
    <div class="comment-inner">

		<?php foreach ( $doctor->get_reviews() as $review ) : $review = (object) $review;
			$reviewer = docpro_get_profile( '', $review->reviewer ); ?>

            <div class="single-comment-box">
                <div class="comment">
                    <figure class="comment-thumb"><img src="<?php echo esc_url( $reviewer->get_avatar_url() ); ?>" alt=""></figure>
                    <h4><?php echo esc_html( $reviewer->display_name ); ?></h4>
                    <span class="comment-time"><i class="fas fa-calendar-alt"></i><?php echo esc_html( date( 'F j, Y', strtotime( $review->datetime ) ) ); ?></span>
					<?php docpro_render_rating( $review->star ); ?>
					<?php printf( '<p>%s</p>', $review->message ); ?>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
</div>

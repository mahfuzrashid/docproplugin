<?php
/**
 * Doctor Details - Nav Item - Reviews
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>

<div class="tab <?php echo esc_attr( $active_class ); ?>" id="reviews">
    <div class="review-box">
        <h3><?php printf( esc_html__( '%s Reviews', 'docpro' ), $doctor->display_name ); ?></h3>
        <div class="rating-inner">
            <div class="rating-box">
                <h2><?php echo esc_html( $doctor->get_average_review_rating() ); ?></h2>
				<?php docpro_render_rating( $doctor->get_average_review_rating() ); ?>
                <span><?php printf( esc_html__( 'Based on %s reviews', 'docpro' ), $doctor->get_reviews_count() ); ?></span>
            </div>
            <div class="rating-pregress">
                <div class="single-progress">
                    <span class="porgress-bar"></span>
                    <div class="text"><p><i class="icon-Star"></i>5 Stars</p></div>
                </div>
                <div class="single-progress">
                    <span class="porgress-bar"></span>
                    <div class="text"><p><i class="icon-Star"></i>4 Stars</p></div>
                </div>
                <div class="single-progress">
                    <span class="porgress-bar"></span>
                    <div class="text"><p><i class="icon-Star"></i>3 Stars</p></div>
                </div>
                <div class="single-progress">
                    <span class="porgress-bar"></span>
                    <div class="text"><p><i class="icon-Star"></i>2 Stars</p></div>
                </div>
                <div class="single-progress">
                    <span class="porgress-bar"></span>
                    <div class="text"><p><i class="icon-Star"></i>1 Stars</p></div>
                </div>
            </div>
        </div>
        <div class="review-inner">
			<?php foreach ( $doctor->get_reviews() as $review ) :

				if ( ! $review || empty( $review ) ) {
					continue;
				}

				$review   = (object) $review;
				$reviewer = docpro_get_profile( '', $review->reviewer ); ?>

                <div class="single-review-box">
                    <figure class="image-box"><img src="<?php echo esc_url( $reviewer->get_avatar_url() ); ?>"></figure>
					<?php docpro_render_rating( $review->star ); ?>
					<?php printf( '<h5>%s <span>- %s</span></h5>', $reviewer->display_name, date( 'F j, Y', strtotime( $review->datetime ) ) ); ?>
					<?php printf( '<h6>%s</h6>', $review->title ); ?>
					<?php printf( '<p>%s</p>', $review->message ); ?>
                </div>
			<?php endforeach; ?>
        </div>

        <div class="btn-box">
            <a href="<?php echo esc_url( $doctor->get_review_form_url() ); ?>" class="theme-btn-one"><?php esc_html_e( 'Submit Review', 'docpro' ); ?> <i class="icon-Arrow-Right"></i></a>
        </div>
    </div>
</div>
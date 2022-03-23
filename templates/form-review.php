<?php
/**
 * Review Form
 */

defined( 'ABSPATH' ) || exit;

global $url_data, $wp, $current_user;


$profile     = docpro_get_profile( '', docpro()->get_args_option( 'id', '', $args ) );
$current_url = sprintf( '%s?%s', site_url( $wp->request ), http_build_query( $url_data ) );
$posted_data = wp_unslash( $_POST );


// Check login
if ( ! is_user_logged_in() ) {
	docpro()->print_notice( sprintf( '%s. <a href="%s">%s</a>',
		esc_html__( 'You must login to review!' ),
		wp_login_url( $current_url ),
		esc_html__( 'Login now' )
	), 'error' );

	return;
}

// Handle review form submission
if ( ! empty( $nonce = docpro()->get_args_option( 'review_nonce', '', $posted_data ) ) && wp_verify_nonce( $nonce, 'review_nonce_val' ) ) {

	$all_reviews   = $profile->get_reviews();
	$all_reviews[] = array(
		'star'     => docpro()->get_args_option( 'star', '', $posted_data ),
		'title'    => docpro()->get_args_option( 'title', '', $posted_data ),
		'message'  => docpro()->get_args_option( 'message', '', $posted_data ),
		'reviewer' => $current_user->ID,
		'datetime' => current_time( 'mysql' ),
	);

	if ( update_user_meta( $profile->ID, '_reviews', $all_reviews ) ) {
		wp_safe_redirect( $profile->get_profile_permalink( 'reviews' ) );
		exit();
	}
}

//delete_user_meta( $profile->ID, '_reviews' );

?>

<section class="submit-review">
    <div class="pattern">
        <div class="pattern-1" style="background-image: url(http://azim.commonsupport.com/Docpro/assets/images/shape/shape-85.png);"></div>
        <div class="pattern-2" style="background-image: url(http://azim.commonsupport.com/Docpro/assets/images/shape/shape-86.png);"></div>
    </div>
    <div class="auto-container">
        <form class="review-box" action="<?php echo esc_url( $current_url ); ?>" method="post">
            <div class="content-box">
                <div class="title-inner">
                    <h3><?php printf( '%s <a href="%s">%s</a>', esc_html__( 'Write a Review for', 'docpro' ), $profile->get_profile_permalink(), $profile->display_name ); ?></h3>
                    <p><?php esc_html_e( 'Don’t hesitate to review me', 'docpro' ); ?></p>
                </div>
                <div class="content-inner">
                    <div class="rating-box">
                        <h4><?php esc_html_e( 'Overall Rating', 'docpro' ); ?></h4>
                        <div class="stars">
                            <input type="radio" name="star" class="star-1" id="star-1" value="1" required/>
                            <label class="star-1" for="star-1">1</label>
                            <input type="radio" name="star" class="star-2" id="star-2" value="2" required/>
                            <label class="star-2" for="star-2">2</label>
                            <input type="radio" name="star" class="star-3" id="star-3" value="3" required/>
                            <label class="star-3" for="star-3">3</label>
                            <input type="radio" name="star" class="star-4" id="star-4" value="4" required/>
                            <label class="star-4" for="star-4">4</label>
                            <input type="radio" name="star" class="star-5" id="star-5" value="5" required/>
                            <label class="star-5" for="star-5">5</label>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-inner">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Title of your review', 'docpro' ); ?></label>
                                <input type="text" name="title" placeholder="If you could say it in one sentance, what would you say?" required="">
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Your name', 'docpro' ); ?></label>
                                <input type="text" name="name" value="<?php echo esc_attr( $current_user->display_name ); ?>" disabled>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Your email', 'docpro' ); ?></label>
                                <input type="email" name="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" disabled>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Review description', 'docpro' ); ?></label>
                                <textarea name="message" placeholder="Write your review here..."></textarea>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <div class="custom-controls-stacked">
                                    <label class="custom-control material-checkbox">
                                        <input type="checkbox" name="terms" class="material-control-input" required>
                                        <span class="material-control-indicator"></span>
                                        <span class="description">I accept <a href="submit.html">terms</a> and <a href="submit.html">conditions</a> and general policy</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
								<?php wp_nonce_field( 'review_nonce_val', 'review_nonce' ); ?>
                                <button type="submit" class="theme-btn-one"><?php esc_html_e( 'Send Message', 'docpro' ); ?> <i class="icon-Arrow-Right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php
/**
 * Doctor Details - Info box
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

//$_followers    = $doctor->get_meta( '_followers', array() );
//$liked_class   = in_array( get_current_user_id(), $_followers ) ? 'liked' : 'unliked';
//$tt_hint_class = is_user_logged_in() ? '' : 'tt--top';
//$tt_hint_label = is_user_logged_in() ? '' : esc_html__( 'You must login to save', 'docpro' );


?>
<div class="clinic-block-one">
    <div class="inner-box">
        <figure class="image-box">
            <img src="<?php echo esc_url( $doctor->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $doctor->display_name ); ?>">
        </figure>
        <div class="content-box">

			<?php docpro_render_likebox( $doctor ); ?>

            <div class="share-box">
                <div class="share-btn">
                    <div class="share-platforms">
                        <a href="<?php echo esc_url( docpro_get_sharer_link( 'facebook' ) ); ?>"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo esc_url( docpro_get_sharer_link( 'twitter' ) ); ?>"><i class="fab fa-twitter"></i></a>
                    </div>
                    <i class="fas fa-share-alt"></i>
                </div>
            </div>
            <ul class="name-box clearfix">
                <li class="name"><h2><?php echo $doctor->display_name; ?></h2></li>
				<?php $doctor->is_available_html(); ?>
				<?php $doctor->is_verified_html(); ?>
            </ul>
            <span class="designation">
                <span><?php echo esc_html( $doctor->get_degrees_text() ); ?></span>
                <span> - </span>
                <span><?php echo esc_html( $doctor->get_primary_speciality() ); ?></span>
            </span>
            <div class="rating-box clearfix">
				<?php docpro_render_rating( $doctor->get_average_review_rating(), array( 'display_count' => true, 'total_reviews' => $doctor->get_reviews_count() ) ); ?>
            </div>
            <div class="text">
                <p><?php echo esc_html( $doctor->description ); ?></p>
            </div>
            <div class="lower-box clearfix">
                <ul class="info clearfix">
                    <li><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $doctor->get_primary_location_formatted() ); ?></li>
                    <li><i class="fas fa-phone"></i><a href="tel:<?php echo esc_attr( $doctor->phone ); ?>"><?php echo esc_html( $doctor->phone ); ?></a>
                    </li>
                </ul>
                <div class="view-map"><a href="<?php echo esc_url( $doctor->get_map_view_url() ); ?>"><?php esc_html_e( 'View Map', 'docpro' ); ?></a></div>
            </div>
        </div>
    </div>
</div>

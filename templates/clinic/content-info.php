<?php
/**
 * Clinic Details - Info box
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>
<div class="clinic-block-one">
    <div class="inner-box">
        <figure class="image-box">
            <img src="<?php echo esc_url( $clinic->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $clinic->display_name ); ?>">
        </figure>
        <div class="content-box">
			<?php docpro_render_likebox( $clinic ); ?>

            <div class="share-box">
                <a href="<?php echo esc_url( $clinic->get_profile_permalink() ); ?>" class="share-btn"><i class="fas fa-share-alt"></i></a>
            </div>
            <ul class="name-box clearfix">
                <li class="name"><h2><?php echo $clinic->display_name; ?></h2></li>
                <li></li>
            </ul>
            <span class="designation"><?php echo esc_html( $clinic->slogan ); ?></span>
            <div class="rating-box clearfix">
				<?php docpro_render_rating( $clinic->get_average_review_rating(), array( 'display_count' => true, 'total_reviews' => $clinic->get_reviews_count() ) ); ?>
            </div>
            <div class="text">
                <p><?php echo esc_html( $clinic->description ); ?></p>
            </div>
            <div class="lower-box clearfix">
                <ul class="info clearfix">
                    <li><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $clinic->get_primary_location_formatted() ); ?></li>
                    <li><i class="fas fa-phone"></i><a href="tel:<?php echo esc_attr( $clinic->phone ); ?>"><?php echo esc_html( $clinic->phone ); ?></a>
                    </li>
                </ul>
                <div class="view-map"><a href="<?php echo esc_url( $clinic->get_map_view_url() ); ?>"><?php esc_html_e( 'View Map', 'docpro' ); ?></a></div>
            </div>
        </div>
    </div>
</div>

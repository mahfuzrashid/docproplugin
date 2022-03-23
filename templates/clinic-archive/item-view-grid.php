<?php
/**
 * Item - List View
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>
<div class="col-lg-6 col-md-6 col-sm-12 team-block">
    <div class="team-block-three">
        <div class="inner-box">
            <figure class="image-box">
                <img src="<?php echo esc_url( $clinic->get_profile_photo() ); ?>" alt="<?php echo esc_attr( $clinic->display_name ); ?>">
	            <?php docpro_render_likebox( $clinic ); ?>
            </figure>
            <div class="lower-content">
                <ul class="name-box clearfix">
                    <li class="name"><h3><a href="<?php echo esc_url( $clinic->get_profile_permalink() ); ?>"><?php echo $clinic->display_name; ?></a></h3></li>
                </ul>
				<?php printf( '<span class="designation">%s</span>', $clinic->slogan ); ?>
                <div class="rating-box clearfix">
	                <?php docpro_render_rating( $clinic->get_average_review_rating(), array( 'display_count' => true, 'total_reviews' => $clinic->get_reviews_count() ) ); ?>
                </div>
                <div class="location-box">
                    <p><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $clinic->get_primary_location_formatted() ); ?></p>
                </div>
                <div class="lower-box clearfix">
					<?php printf( '<span class="text">%s</span>', esc_html( $clinic->get_availability_status() ) ); ?>
                    <a href="<?php echo esc_url( $clinic->get_profile_permalink() ); ?>"><?php esc_html_e( 'Visit Now', 'docpro' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

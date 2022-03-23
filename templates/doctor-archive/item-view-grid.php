<?php
/**
 * Item - List View
 */

defined( 'ABSPATH' ) || exit;

global $doctor;


?>
<div class="col-lg-6 col-md-6 col-sm-12 team-block">
    <div class="team-block-three">
        <div class="inner-box">
            <figure class="image-box">
                <img src="<?php echo esc_url( $doctor->get_avatar_url() ); ?>" alt="<?php echo esc_attr( $doctor->display_name ); ?>">
	            <?php docpro_render_likebox( $doctor ); ?>
            </figure>
            <div class="lower-content">
                <ul class="name-box clearfix">
                    <li class="name"><h3><a href="<?php echo esc_url( $doctor->get_profile_permalink() ); ?>"><?php echo $doctor->display_name; ?></a></h3></li>
                </ul>
				<?php printf( '<span class="designation">%s</span>', $doctor->get_designation_html() ); ?>
                <div class="rating-box clearfix">
	                <?php docpro_render_rating( $doctor->get_average_review_rating(), array( 'display_count' => true, 'total_reviews' => $doctor->get_reviews_count() ) ); ?>
                </div>
                <div class="location-box">
                    <p><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $doctor->get_primary_location_formatted() ); ?></p>
                </div>
                <div class="lower-box clearfix">
					<?php printf( '<span class="text">%s</span>', esc_html( $doctor->visiting_time ) ); ?>
                    <a href="<?php echo esc_url( $doctor->get_profile_permalink() ); ?>"><?php esc_html_e( 'Visit Now', 'docpro' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

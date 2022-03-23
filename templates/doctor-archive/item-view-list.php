<?php
/**
 * Item - List View
 */

defined( 'ABSPATH' ) || exit;

global $doctor, $docpro_query;

?>
<div class="<?php echo esc_attr( $docpro_query->get( 'class_list' ) ); ?>">
    <div class="inner-box">
        <div class="pattern">
            <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-24.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
            <div class="pattern-2" style="background-image: url(<?php printf( '%sassets/images/shape/shape-25.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
        </div>
        <figure class="image-box"><img src="<?php echo esc_url( $doctor->get_avatar_url() ); ?>" alt="<?php echo esc_attr( $doctor->display_name ); ?>"></figure>
        <div class="content-box">
			<?php docpro_render_likebox( $doctor ); ?>
            <ul class="name-box clearfix">
                <li class="name"><h3><a href="<?php echo esc_url( $doctor->get_profile_permalink() ); ?>"><?php echo $doctor->display_name; ?></a></h3></li>
	            <?php $doctor->is_available_html(); ?>
	            <?php $doctor->is_verified_html(); ?>
            </ul>
			<?php printf( '<span class="designation">%s</span>', $doctor->get_designation_html() ); ?>
            <div class="text">
                <p><?php echo esc_html( $doctor->description ); ?></p>
            </div>
            <div class="rating-box clearfix">
				<?php docpro_render_rating( $doctor->get_average_review_rating(), array( 'display_count' => true, 'total_reviews' => $doctor->get_reviews_count() ) ); ?>
				<?php printf( '<div class="link">%s</div>', esc_html( $doctor->visiting_time ) ); ?>
            </div>
            <div class="location-box">
                <p><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $doctor->get_primary_location_formatted() ); ?></p>
            </div>
            <div class="btn-box"><a href="<?php echo esc_url( $doctor->get_profile_permalink() ); ?>"><?php esc_html_e( 'Visit Now', 'docpro' ); ?></a></div>
        </div>
    </div>
</div>

<?php
/**
 * Dashboard - Favourite Doctors
 */

defined( 'ABSPATH' ) || exit;

global $patient;

$fav_doctors = $patient->get_favourites( array( 'role' => 'doctor' ) );

?>
<div class="outer-container">
    <div class="favourite-doctors">
        <div class="title-box">
            <h3><?php esc_html_e( 'Favourite List', 'docpro' ); ?></h3>
        </div>
        <div class="doctors-list">
            <div class="row clearfix">
				<?php foreach ( $fav_doctors as $fav_user ) : $doctor = docpro_get_profile( 'doctor', $fav_user->ID ); ?>
                    <div class="col-xl-3 col-lg-6 col-md-12 doctors-block">
                        <div class="team-block-three">
                            <div class="inner-box">
                                <span class="docpro-remove_fav" data-entity="<?php echo esc_attr( $doctor->ID ); ?>"><?php esc_html_e( 'Remove', 'docpro' ); ?></span>
                                <figure class="image-box">
                                    <img src="<?php echo esc_url( $doctor->get_profile_photo() ); ?>" alt="<?php echo esc_attr( $doctor->display_name ); ?>">
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
										<?php printf( '<span class="text">%s</span>', esc_html( $doctor->get_availability_status() ) ); ?>
                                        <a href="<?php echo esc_url( $doctor->get_profile_permalink() ); ?>"><?php esc_html_e( 'Visit Now', 'docpro' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

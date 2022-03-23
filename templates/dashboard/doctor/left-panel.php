<?php
/**
 * Dashboard - left panel
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>
<div class="profile-box">
    <div class="upper-box">
        <figure class="profile-image">
            <img src="<?php echo esc_url( $doctor->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $doctor->display_name ); ?>">
        </figure>
        <div class="title-box centred">
            <div class="inner">
                <h3><?php echo esc_html( $doctor->display_name ); ?></h3>
				<?php printf( '<p>%s</p>', $doctor->get_designation_html() ); ?>
            </div>
        </div>
    </div>
    <div class="profile-info">
        <ul class="list clearfix">
			<?php docpro_dashboard_nav_links( $doctor->get_dashboard_navigation() ); ?>
        </ul>
    </div>
</div>

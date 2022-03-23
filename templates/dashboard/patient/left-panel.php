<?php
/**
 * Dashboard - left panel
 */

defined( 'ABSPATH' ) || exit;

global $patient;

?>

<div class="profile-box">
    <div class="upper-box">
        <figure class="profile-image">
            <img src="<?php echo esc_url( $patient->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $patient->display_name ); ?>">
        </figure>
        <div class="title-box centred">
            <div class="inner">
                <h3><?php echo esc_html( $patient->display_name ); ?></h3>
            </div>
        </div>
    </div>
    <div class="profile-info">
        <ul class="list clearfix">
			<?php docpro_dashboard_nav_links( $patient->get_dashboard_navigation() ); ?>
        </ul>
    </div>
</div>


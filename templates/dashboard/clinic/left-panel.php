<?php
/**
 * Dashboard - left panel
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>

<div class="profile-box">
    <div class="upper-box">
        <figure class="profile-image">
            <img src="<?php echo esc_url( $clinic->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $clinic->display_name ); ?>">
        </figure>
        <div class="title-box centred">
            <div class="inner">
                <h3><?php echo esc_html( $clinic->display_name ); ?></h3>
				<?php printf( '<p>%s</p>', $clinic->slogan ); ?>
            </div>
        </div>
    </div>
    <div class="profile-info">
        <ul class="list clearfix">
			<?php docpro_dashboard_nav_links( $clinic->get_dashboard_navigation() ); ?>
        </ul>
    </div>
</div>

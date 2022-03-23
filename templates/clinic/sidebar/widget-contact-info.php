<?php
/**
 * Clinic Details - Sidebar - Contact Info
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

$social_profiles = is_array( $clinic->social_profiles ) ? $clinic->social_profiles : array();
$social_profiles = array_map( function ( $social_profile ) {
	return sprintf( '<li><a href="%s" title="%s"><i class="%s"></i></a></li>',
		docpro()->get_args_option( 'url', '', $social_profile ),
		docpro()->get_args_option( 'platform', '', $social_profile ),
		docpro()->get_args_option( 'icon', '', $social_profile )
	);
}, $social_profiles );


?>

<div class="info-widget">
    <div class="info-title">
        <h3><?php esc_html_e( 'Contact Info', 'docpro' ); ?></h3>
        <p><?php echo esc_html( $clinic->slogan ); ?></p>
    </div>
    <div class="info-inner">
        <ul class="info-list clearfix">
            <li>
                <h4><?php esc_html_e( 'Location', 'docpro' ); ?></h4>
                <p><?php echo esc_html( $clinic->get_primary_location_formatted() ); ?></p>
            </li>
            <li>
                <h4><?php esc_html_e( 'Phone', 'docpro' ); ?></h4>
                <p><?php printf( '<a href="tel:%1$s">%1$s</a>', $clinic->phone ); ?></p>
            </li>
            <li>
                <h4><?php esc_html_e( 'Fax', 'docpro' ); ?></h4>
                <p><?php printf( '<a href="tel:%1$s">%1$s</a>', $clinic->fax ); ?></p>
            </li>
            <li>
                <h4><?php esc_html_e( 'Email', 'docpro' ); ?></h4>
                <p><?php printf( '<a href="mailto:%1$s">%1$s</a>', $clinic->user_email ); ?></p>
            </li>
            <li>
                <h4><?php esc_html_e( 'Website', 'docpro' ); ?></h4>
                <p><?php printf( '<a href="%1$s">%1$s</a>', $clinic->user_url ); ?></p>
            </li>
        </ul>
        <div class="social-box">
            <h4><?php esc_html_e( 'Social Profile', 'docpro' ); ?></h4>
			<?php printf( '<ul class="social-links clearfix">%s</ul>', implode( '', $social_profiles ) ); ?>
        </div>
    </div>
</div>
<?php
/**
 * Login
 */

defined( 'ABSPATH' ) || exit;

global $wp;

$posted_data = wp_unslash( $_POST );

if ( wp_verify_nonce( docpro()->get_args_option( 'docpro_login_val', '', $posted_data ), 'docpro_login' ) ) {

	$user_login  = docpro()->get_args_option( 'user_login', '', $posted_data );
	$password    = docpro()->get_args_option( 'password', '', $posted_data );
	$redirect    = docpro()->get_args_option( 'redirect', '', $posted_data );
	$user_trying = get_user_by( 'login', $user_login );

	// need to use wp_authenticate

	if ( ! is_wp_error( $user_trying = wp_authenticate( $user_login, $password ) ) ) {

		wp_set_auth_cookie( $user_trying->ID, true );
		wp_set_current_user( $user_trying->ID, $user_login );
		do_action( 'wp_login', $user_login, $user_trying );

		wp_safe_redirect( $redirect );
		exit;
	} else {
		printf( '<p class="docpro-notice docpro-notice-error">%s</p>', $user_trying->get_error_message() );
	}
}

?>
<section class="registration-section">
    <div class="pattern">
        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-85.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-86.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
    </div>
    <div class="auto-container">
        <div class="inner-box">
            <div class="bg-color-3 content-box ">
                <div class="title-box">
                    <h3><?php esc_html_e( 'Login', 'docpro' ); ?></h3>
                    <a href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Not a user?', 'docpro' ); ?></a>
                </div>
                <div class="inner">
                    <form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" method="post" class="registration-form">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Email', 'docpro' ); ?></label>
                                <input type="text" name="user_login" placeholder="<?php esc_attr_e( 'Enter username or email', 'docpro' ); ?>" required="">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Password', 'docpro' ); ?></label>
                                <input type="password" name="password" placeholder="<?php esc_attr_e( 'Enter password', 'docpro' ); ?>" required="">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <div class="forgot-passowrd clearfix">
                                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forget Password?', 'docpro' ); ?></a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
								<?php wp_nonce_field( 'docpro_login', 'docpro_login_val' ); ?>
                                <input type="hidden" name="redirect" value="<?php echo esc_url( site_url( $wp->request ) ); ?>">
                                <button type="submit" name="wp-submit" class="theme-btn-one"><?php esc_html_e( 'Login Now', 'docpro' ); ?><i class="icon-Arrow-Right"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="login-now"><p><?php esc_html_e( 'Don’t have an account?', 'docpro' ); ?> <a href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Register Now', 'docpro' ); ?></a></p></div>

					<?php
					if ( defined( 'OA_SOCIAL_LOGIN_VERSION' ) ) {
						printf( '<div class="login-with-socials">%s</div>', do_shortcode( '[oa_social_login]' ) );
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>

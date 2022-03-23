<?php
/**
 * Login
 */

defined( 'ABSPATH' ) || exit;

global $wp;

$posted_data = wp_unslash( $_POST );

if ( wp_verify_nonce( docpro()->get_args_option( 'docpro_register_val', '', $posted_data ), 'docpro_register' ) ) {

	$first_name       = docpro()->get_args_option( 'first_name', '', $posted_data );
	$last_name        = docpro()->get_args_option( 'last_name', '', $posted_data );
	$user_name        = docpro()->get_args_option( 'user_name', '', $posted_data );
	$user_email       = docpro()->get_args_option( 'user_email', '', $posted_data );
	$user_type        = docpro()->get_args_option( 'user_type', '', $posted_data );
	$password         = docpro()->get_args_option( 'password', '', $posted_data );
	$confirm_password = docpro()->get_args_option( 'confirm_password', '', $posted_data );
	$terms            = docpro()->get_args_option( 'terms', '', $posted_data );

	if ( $password == $confirm_password && $terms == 'on' && ! email_exists( $user_email ) ) {
		if ( ! is_wp_error( $user_id = wp_create_user( $user_name, $confirm_password, $user_email ) ) ) {

			$new_user = get_user_by( 'id', $user_id );

			if ( in_array( $user_type, array( 'doctor', 'patient', 'clinic' ) ) ) {
				$new_user->remove_role( 'subscriber' );
				$new_user->add_role( $user_type );
				update_user_meta( $new_user->ID, '_user_type', $user_type );
			}

			printf( '<p class="docpro-notice docpro-notice-success">%s</p>', esc_html__( 'Registration successful! Please check email inbox.' ) );
		}
	}
}

?>
<section class="registration-section">
    <div class="pattern">
        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-85.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-86.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
    </div>
    <div class="auto-container">
        <div class="inner-box bg-color-3">
            <div class="content-box">
                <div class="title-box">
                    <h3><?php esc_html_e( 'Registration', 'docpro' ); ?></h3>
                    <a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login', 'docpro' ); ?></a>
                </div>
                <div class="inner">
                    <form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" method="post" class="registration-form">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label><?php esc_html_e( 'First name', 'docpro' ); ?></label>
                                <input type="text" name="first_name" placeholder="<?php esc_html_e( 'John', 'docpro' ); ?>" required="">
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Last name', 'docpro' ); ?></label>
                                <input type="text" name="last_name" placeholder="<?php esc_html_e( 'Doe', 'docpro' ); ?>" required="">
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Username', 'docpro' ); ?></label>
                                <input type="text" name="user_name" placeholder="<?php esc_html_e( 'myusername', 'docpro' ); ?>" required="">
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Email', 'docpro' ); ?></label>
                                <input type="email" name="user_email" placeholder="<?php esc_html_e( 'me@john.doe', 'docpro' ); ?>" required="">
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'User Type', 'docpro' ); ?></label>
                                <div class="custom-check-box">
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control material-checkbox">
                                            <input type="radio" name="user_type" required class="material-control-input" value="<?php echo esc_attr( 'doctor', 'docpro' ); ?>">
                                            <span class="material-control-indicator"></span>
                                            <span class="description"><?php esc_html_e( 'Doctor', 'docpro' ); ?></span>
                                        </label>

                                        <label class="custom-control material-checkbox">
                                            <input type="radio" name="user_type" required class="material-control-input" value="<?php echo esc_attr( 'clinic', 'docpro' ); ?>">
                                            <span class="material-control-indicator"></span>
                                            <span class="description"><?php esc_html_e( 'Clinic', 'docpro' ); ?></span>
                                        </label>

                                        <label class="custom-control material-checkbox">
                                            <input type="radio" name="user_type" required class="material-control-input" value="<?php echo esc_attr( 'patient', 'docpro' ); ?>">
                                            <span class="material-control-indicator"></span>
                                            <span class="description"><?php esc_html_e( 'Patient', 'docpro' ); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Password', 'docpro' ); ?></label>
                                <input type="password" name="password" id="passwordField" placeholder="<?php echo esc_attr( '******', 'docpro' ); ?>" required="">
                                <label><input type="checkbox" onclick="displayPassword()"><?php esc_html_e( 'Show Password', 'docpro' ); ?></label>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label><?php esc_html_e( 'Confirm password', 'docpro' ); ?></label>
                                <input type="password" name="confirm_password" placeholder="<?php echo esc_attr( '******', 'docpro' ); ?>" required="">
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <div class="custom-check-box">
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" name="terms" required class="material-control-input">
                                            <span class="material-control-indicator"></span>
                                            <span class="description"><?php esc_html_e( 'I accept terms, conditions and general policy', 'docpro' ); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
								<?php wp_nonce_field( 'docpro_register', 'docpro_register_val' ); ?>
                                <button type="submit" class="theme-btn-one"><?php esc_html_e( 'Register now', 'docpro' ); ?><i class="icon-Arrow-Right"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="login-now"><p><?php esc_html_e( 'Already have an account?', 'docpro' ); ?> <a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login now', 'docpro' ); ?></a></p></div>
                </div>
            </div>
        </div>
    </div>
</section>

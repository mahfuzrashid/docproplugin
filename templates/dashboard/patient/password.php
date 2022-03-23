<?php
/**
 * Dashboard - Password Change
 */

defined( 'ABSPATH' ) || exit;

global $patient, $wp;


$posted_data = wp_unslash( $_POST );

if ( wp_verify_nonce( docpro()->get_args_option( 'docpro_pw_nonce_val', '', $posted_data ), 'docpro_pw_nonce' ) ) {

	$old_password     = docpro()->get_args_option( 'old_password', '', $posted_data );
	$new_password     = docpro()->get_args_option( 'new_password', '', $posted_data );
	$confirm_password = docpro()->get_args_option( 'confirm_password', '', $posted_data );

	if ( wp_check_password( $old_password, $patient->user_pass, $patient->ID ) && $new_password == $confirm_password ) {
		wp_set_password( $new_password, $patient->ID );

		wp_safe_redirect( docpro()->get_args_option( 'current_url', '', $posted_data ) );
		exit();
	}
}


?>

<div class="add-listing change-password">
	<form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" method="post">
		<div class="single-box">
			<div class="title-box">
				<h3><?php esc_html_e( 'Change Password', 'docpro' ); ?></h3>
			</div>
			<div class="inner-box">
				<div class="row clearfix">
					<div class="col-lg-6 col-md-12 col-sm-12 form-group">
						<label><?php esc_html_e( 'Old Password', 'docpro' ); ?></label>
						<input type="password" name="old_password" required="">
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 form-group"></div>
					<div class="col-lg-6 col-md-12 col-sm-12 form-group">
						<label><?php esc_html_e( 'New Password', 'docpro' ); ?></label>
						<input type="password" name="new_password" required="">
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 form-group"></div>
					<div class="col-lg-6 col-md-12 col-sm-12 form-group">
						<label><?php esc_html_e( 'Confirm Password', 'docpro' ); ?></label>
						<input type="password" name="confirm_password" required="">
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 form-group"></div>
				</div>
			</div>
		</div>
		<div class="btn-box">
			<?php wp_nonce_field( 'docpro_pw_nonce', 'docpro_pw_nonce_val' ); ?>
			<input type="hidden" name="current_url" value="<?php echo esc_url( site_url( $wp->request ) ); ?>">
			<button type="submit" class="theme-btn-one"><?php esc_html_e( 'Save Change', 'docpro' ); ?><i class="icon-Arrow-Right"></i></button>
			<button type="reset" class="cancel-btn"><?php esc_html_e( 'Cancel', 'docpro' ); ?></button>
		</div>
	</form>
</div>
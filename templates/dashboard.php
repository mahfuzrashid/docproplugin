<?php
/**
 * Dashboard
 */

defined( 'ABSPATH' ) || exit;

global $current_user, $doctor, $patient, $clinic;

?>
    <div class="doctors-dashboard">
		<?php
		if ( in_array( 'doctor', $current_user->roles ) ) :

			$doctor = docpro_get_profile( 'doctor', $current_user->ID );
			docpro_render_dashboard_content( 'doctor' );

        elseif ( in_array( 'clinic', $current_user->roles ) ) :

			$clinic = docpro_get_profile( 'clinic', $current_user->ID );
			docpro_render_dashboard_content( 'clinic' );

        elseif ( in_array( 'patient', $current_user->roles ) ) :

			$patient = docpro_get_profile( 'patient', $current_user->ID );
			docpro_render_dashboard_content( 'patient' );

        elseif ( is_user_logged_in() ) :

			docpro()->print_notice( esc_html__( 'You are not authorize to access this page', 'docpro' ), 'error' );
		else :

			docpro_get_template( 'form-login.php' );

		endif;
		?>
    </div>
<?php

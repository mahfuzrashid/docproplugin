<?php
/**
 * Dashboard - Clinic Main
 */

defined( 'ABSPATH' ) || exit;

global $clinic, $wp;

$posted_data = wp_unslash( $_POST );

if ( wp_verify_nonce( docpro()->get_args_option( 'docpro_profile_val', '', $posted_data ), 'docpro_profile' ) ) {

	$data_args   = array(
		'first_name' => docpro()->get_args_option( 'first_name', $clinic->first_name, $posted_data ),
		'last_name'  => docpro()->get_args_option( 'last_name', $clinic->last_name, $posted_data ),
	);
	$meta_fields = docpro()->metaBoxes->get_meta_fields( 'clinic' );
	$meta_fields = array_map( function ( $field ) {
		return $field['id'];
	}, $meta_fields );

	foreach ( $meta_fields as $meta_key ) {
		$data_args[ $meta_key ] = docpro()->get_args_option( $meta_key, '', $posted_data );
	}

	if ( $ret = $clinic->update_data( $data_args ) ) {
		wp_safe_redirect( site_url( $wp->request ) );
		exit();
	}
}

?>


<form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" class="add-listing my-profile" method="post">

	<?php docpro()->metaBoxes->render_frontend_user_form_fields( $clinic->ID, 'clinic' ); ?>

    <!-- Save Changes -->
    <div class="btn-box">
		<?php wp_nonce_field( 'docpro_profile', 'docpro_profile_val' ); ?>
        <button type="submit" class="theme-btn-one"><?php esc_html_e( 'Save Change', 'docpro' ); ?><i class="icon-Arrow-Right"></i></button>
        <button type="reset" class="cancel-btn"><?php esc_html_e( 'Cancel', 'docpro' ); ?></button>
    </div>
</form>

<?php
/**
 * Clinic Details - Nav Item - Doctors
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic, $docpro_query;

$query_args   = array(
	'role'       => 'doctor',
	'fields'     => 'ID',
	'number'     => 99999999,
	'include'    => $clinic->doctors,
	'class_list' => 'team-block-one',
);
$docpro_query = docpro_get_archive_query( $query_args );

?>

<div class="tab <?php echo esc_attr( $active_class ); ?>" id="doctors">
    <div class="onboard-doctors">
        <h3><?php esc_html_e( 'Onboard Doctors', 'docpro' ); ?></h3>

		<?php printf( '%s', docpro_get_archive_items() ); ?>
    </div>
</div>
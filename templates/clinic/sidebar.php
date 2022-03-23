<?php
/**
 * Clinic Details - Sidebar Widget - Booking
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
    <div class="clinic-sidebar">
		<?php docpro_get_template_part( 'clinic/sidebar/widget-contact', 'form' ); ?>

		<?php docpro_get_template_part( 'clinic/sidebar/widget-contact', 'info' ); ?>
    </div>
</div>
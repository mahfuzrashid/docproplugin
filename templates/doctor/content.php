<?php
/**
 * Doctor Details - Content
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="col-lg-8 col-md-12 col-sm-12 content-side">
    <div class="clinic-details-content doctor-details-content">
		<?php docpro_get_template_part( 'doctor/content', 'info' ); ?>

		<?php docpro_get_template_part( 'doctor/content', 'tab' ); ?>
    </div>
</div>
<?php
/**
 * Single Doctor
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="doctor-details bg-color-3">
    <div class="auto-container">
        <div class="row clearfix">
			<?php docpro_get_template( 'doctor/content.php' ); ?>

			<?php docpro_get_template( 'doctor/sidebar.php' ); ?>
        </div>
    </div>
</div>

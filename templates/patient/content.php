<?php
/**
 * Patient Details - Content
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $patient;

?>

<div class="col-lg-8 col-md-12 col-sm-12 content-side">
    <div class="clinic-details-content doctor-details-content">
		<?php docpro_get_template_part( 'patient/content', 'info' ); ?>
    </div>

    To change this template: <?php echo __FILE__; ?> <br><br>

	<?php echo '<pre>';
	print_r( $patient );
	echo '</pre>'; ?>

</div>
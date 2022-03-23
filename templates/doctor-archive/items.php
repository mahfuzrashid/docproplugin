<?php
/**
 * Doctors - Items
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="wrapper docpro-items-wrapper list">
	<?php printf( '<div class="clinic-list-content list-item">%s</div>', docpro_get_archive_items() ); ?>

    <div class="clinic-grid-content">
		<?php printf( '<div class="row clearfix">%s</div>', docpro_get_archive_items( 'grid' ) ); ?>
    </div>
</div>

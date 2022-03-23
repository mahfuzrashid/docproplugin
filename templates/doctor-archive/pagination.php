<?php
/**
 * Doctors - Pagination
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="pagination-wrapper docpro-pagination">
	<?php docpro_paginate_links( array( 'type' => 'list' ) ); ?>
</div>
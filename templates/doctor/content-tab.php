<?php
/**
 * Doctor Details - Info box
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

$nav_items  = docpro_get_content_nav_tabs();
$query_var  = get_query_var( 'doctor' );
$query_var  = explode( '/', $query_var );
$active_tab = isset( $query_var[1] ) ? $query_var[1] : 'overview';

?>
<div class="tabs-box">
    <div class="tab-btn-box centred">
        <ul class="tab-btns tab-buttons clearfix">
			<?php foreach ( $nav_items as $key => $label ) {
				printf( '<li class="tab-btn tab-%1$s %2$s" data-target="%1$s">%3$s</li>', $key, $key === $active_tab ? 'active-btn' : '', $label );
			} ?>
        </ul>
    </div>
    <div class="tabs-content">
		<?php foreach ( $nav_items as $key => $label ) {
			docpro_get_template_part( 'doctor/nav/item', $key,
				array(
					'nav_item'       => $key,
					'nav_item_label' => $label,
					'active_class'   => $key === $active_tab ? 'active-tab' : '',
				)
			);
		} ?>
    </div>
</div>

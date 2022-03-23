<?php
/**
 * Archive - Header
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $docpro_query, $url_data;


$pagenum      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$first        = ( ( $pagenum - 1 ) * $docpro_query->query_vars['number'] ) + 1;
$total_founds = $docpro_query->get_total();
$displaying   = $docpro_query->get( 'number' ) > $total_founds ? $total_founds : $docpro_query->get( 'number' );
$first_to     = $displaying > $total_founds ? $displaying * $pagenum : $displaying;
//$first_to     = $first_to == $displaying ? $first : $first_to;

?>
<div class="item-shorting clearfix">
    <div class="left-column pull-left">
        <h6><?php printf( esc_html__( 'Displaying %s - %s of %s %ss', 'docpro' ), $first, $first_to, $total_founds, $docpro_query->get( 'role' ) ) ?></h6>
    </div>

    <div class="right-column pull-right clearfix">
        <div class="short-box clearfix">
            <div class="select-box">
				<?php
				docpro()->pbSettings->generate_select(
					array(
						'id'        => 'sort',
						'args'      => array(
							''         => esc_html__( 'Name A - Z', 'docpro' ),
							'name_z_a' => esc_html__( 'Name Z - A', 'docpro' ),
						),
						'class'     => 'wide docpro-filter',
						'value'     => docpro()->get_args_option( 'sort', '', $url_data ),
						'data_attr' => 'data-form=archive-search-form',
					)
				);
				?>
            </div>
        </div>
        <div class="menu-box docpro-items-controller">
            <button type="button" class="list-view on" data-target="list"><i class="icon-List"></i></button>
            <button type="button" class="grid-view" data-target="grid"><i class="icon-Grid"></i></button>
        </div>
    </div>
</div>

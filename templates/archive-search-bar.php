<?php
/**
 * Archive - Search Bar
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $docpro_query, $wp, $url_data;

$selected_dept  = docpro()->get_args_option( 'dept', '', $url_data );
$selected_loc   = docpro()->get_args_option( 'loc', '', $url_data );
$searched_name  = docpro()->get_args_option( 'n', '', $url_data );
$checked_status = docpro()->get_args_option( 'status', '', $url_data );

?>
<div class="select-field bg-color-3 mb-5">
    <div class="auto-container">
        <div class="content-box">
            <div class="form-inner clearfix">
                <div class="search-fields">
                    <div class="form-group clearfix">

						<?php docpro()->pbSettings->generate_select( array( 'id' => 'dept', 'args' => docpro()->get_departments(), 'class' => 'wide docpro-filter', 'data_attr' => 'data-form=archive-search-form', 'value' => $selected_dept, 'placeholder' => esc_html__( 'Select Department', 'docpro' ) ) ); ?>

						<?php docpro()->pbSettings->generate_select( array( 'id' => 'loc', 'args' => 'POSTS_%location%', 'class' => 'wide docpro-filter ignore', 'data_attr' => 'data-form=archive-search-form', 'value' => $selected_loc, 'placeholder' => esc_html__( 'Select Location', 'docpro' ) ) ); ?>

						<?php docpro()->pbSettings->generate_text( array( 'id' => 'n', 'placeholder' => esc_html__( 'Ex. Name..', 'docpro' ), 'value' => $searched_name ) ); ?>

                        <button type="submit"><i class="icon-Arrow-Right"></i></button>
                    </div>
                </div>
                <ul class="select-box clearfix">
                    <li>
                        <div class="single-checkbox">
                            <input class="docpro-filter" data-form="archive-search-form" type="radio" name="status" id="status-all" value="" <?php checked( $checked_status, '' ); ?>>
                            <label for="status-all"><span></span><?php esc_html_e( 'All', 'docpro' ); ?></label>
                        </div>
                    </li>
                    <li>
                        <div class="single-checkbox">
                            <input class="docpro-filter" data-form="archive-search-form" type="radio" name="status" id="status-available" value="available" <?php checked( $checked_status, 'available' ); ?>>
                            <label for="status-available"><span></span><?php esc_html_e( 'Available', 'docpro' ); ?></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
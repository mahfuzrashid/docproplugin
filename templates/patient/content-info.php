<?php
/**
 * Patient Details - Info box
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $patient;

?>
<div class="clinic-block-one">
    <div class="inner-box">
        <figure class="image-box">
            <img src="<?php echo esc_url( $patient->get_avatar_url() ); ?>"
                 alt="<?php echo esc_attr( $patient->display_name ); ?>">
        </figure>
        <div class="content-box">
            <ul class="name-box clearfix">
                <li class="name"><h2><?php echo $patient->display_name; ?></h2></li>
                <li></li>
            </ul>

            <div class="text">
                <p><?php echo esc_html( $patient->description ); ?></p>
            </div>
        </div>
    </div>
</div>

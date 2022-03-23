<?php
/**
 * Clinic Details - Sidebar - Contact Form
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

?>

<div class="form-widget">
    <div class="form-title">
        <h3><?php esc_html_e( 'Contact Us', 'docpro' ); ?></h3>
        <p><?php esc_html_e( 'Don’t hesitate to contact Us', 'docpro' ); ?></p>
    </div>
    <div class="form-inner">
		<?php echo do_shortcode( sprintf( '[contact-form-7 id="%s"]', $clinic->get_contact_form_id() ) ); ?>
    </div>
</div>
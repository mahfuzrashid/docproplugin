<?php
/**
 * Doctor Details - Sidebar - Booking
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor, $wp;

$posted_data = wp_unslash( $_POST );

// Handle review form submission
if ( ! empty( $nonce = docpro()->get_args_option( 'booking_nonce', '', $posted_data ) ) && wp_verify_nonce( $nonce, 'booking_nonce_val' ) ) {
	if ( (bool) docpro_add_service_to_cart( $posted_data, $doctor ) ) {
		wp_safe_redirect( wc_get_checkout_url() );
		exit();
	}
}

?>

<div class="form-widget">
    <div class="form-title">
        <h3><?php esc_html_e( 'Book Appointment', 'docpro' ); ?></h3>
        <p><?php echo esc_html( $doctor->visiting_time ); ?></p>
    </div>
    <form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" class="form-inner" method="post">
        <div class="appointment-time">
            <div class="form-group">
				<?php
				docpro()->pbSettings->generate_datepicker( array(
					'id'            => 'date',
					'required'      => true,
					'type'          => 'datepicker',
					'placeholder'   => esc_html( '22-12-2020' ),
					'field_options' => array(
						'dateFormat' => esc_attr( 'dd-mm-yy' ),
					),
				) );
				?>
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="form-group">
				<?php
				docpro()->pbSettings->generate_timepicker( array(
					'id'          => 'time',
					'required'    => true,
					'placeholder' => esc_html( '11:00 AM' ),
					'type'        => 'timepicker',
				) );
				?>
                <i class="far fa-clock"></i>
            </div>
        </div>
        <div class="choose-service">
            <h4><?php esc_html_e( 'Choose Service', 'docpro' ); ?></h4>

			<?php if ( is_array( $doctor->services ) ) : foreach ( $doctor->services as $service ) : ?>
				<?php if ( (bool) docpro()->get_args_option( 'is_active', false, $service ) && ! empty( $service_id = docpro()->get_args_option( 'id', '', $service ) ) ) : ?>
                    <div class="custom-check-box">
                        <div class="custom-controls-stacked">
                            <label class="custom-control material-checkbox">
                                <input type="radio" name="service" class="material-control-input" value="<?php echo esc_attr( $service_id ); ?>">
                                <span class="material-control-indicator"></span>
								<?php printf( '<span class="description">%s <span class="price">%s</span></span>',
									docpro()->get_args_option( 'title', '', $service ),
									docpro()->get_args_option( 'price', '', $service )
								); ?>
                            </label>
                        </div>
                    </div>
				<?php endif; ?>
			<?php endforeach; endif; ?>

			<?php if ( empty( $doctor->services ) ) : ?>
                <p class="docpro-error"><?php esc_html_e( 'No services found!', 'docpro' ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $doctor->services ) ) : ?>
                <div class="btn-box">
					<?php wp_nonce_field( 'booking_nonce_val', 'booking_nonce' ); ?>
                    <button type="submit" class="theme-btn-one"><?php esc_html_e( 'Book Appointment', 'docpro' ); ?> <i class="icon-Arrow-Right"></i></button>
                </div>
			<?php endif; ?>
        </div>
    </form>
</div>
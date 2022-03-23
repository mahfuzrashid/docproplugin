<?php
/**
 * Dashboard - Doctor Appointments
 */

defined( 'ABSPATH' ) || exit;

global $doctor, $wp;

$searched_data   = wp_unslash( $_GET );
$booking_args    = array();
$searched_status = '';

if ( ! empty( $searched_status = docpro()->get_args_option( 'status', '', $searched_data ) ) ) {
	$booking_args['meta_query'][] = array(
		'key'     => '_status',
		'value'   => $searched_status,
		'compare' => '=',
	);
}

?>

<div class="appointment-list">
    <div class="upper-box clearfix">
        <div class="text pull-left">
            <h3><?php esc_html_e( 'Appointment Lists', 'docpro' ); ?></h3>
        </div>

        <form id="appointmentFilterForm" action="<?php echo esc_url( site_url( $wp->request ) ); ?>" method="get">
            <div class="select-box pull-right">
                <select class="wide docpro-auto-submit" data-form-id="appointmentFilterForm" name="status">
                    <option value=""><?php esc_html_e( 'Any Status', 'docpro' ); ?></option>
                    <option value="approved" <?php selected( $searched_status, 'approved' ); ?>><?php esc_html_e( 'Approved', 'docpro' ); ?></option>
                    <option value="pending" <?php selected( $searched_status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'docpro' ); ?></option>
                    <option value="cancelled" <?php selected( $searched_status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'docpro' ); ?></option>
                </select>
            </div>
        </form>
    </div>

	<?php foreach ( $doctor->get_booking_ids( $booking_args ) as $booking_id ) : $booking = docpro_get_booking( $booking_id ); ?>

        <div class="single-item">
			<?php printf( '<figure class="image-box"><img src="%s" alt="%s"></figure>', $booking->patient->get_avatar_url(), $booking->patient->display_name ); ?>
            <div class="inner">
                <h4><?php echo esc_html( $booking->patient->display_name ); ?></h4>
                <ul class="info-list clearfix">
                    <li><i class="fas fa-clock"></i><?php echo esc_html( $booking->get_date_time() ); ?></li>

					<?php if ( ! empty( $location = $booking->patient->get_primary_location_formatted() ) ) : ?>
                        <li><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $location ); ?></li>
					<?php endif; ?>

                    <li><i class="fas fa-hourglass-start"></i><?php echo esc_html( $booking->service_title ); ?></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:anna@example.com"><?php echo esc_html( $booking->patient->user_email ); ?></a></li>

					<?php if ( ! empty( $phone = $booking->patient->phone ) ) : ?>
                        <li><i class="fas fa-phone"></i><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></li>
					<?php endif; ?>
                </ul>

                <ul class="confirm-list clearfix">
                    <li class="docpro-appointment-files">
						<?php foreach ( $booking->get_files() as $file_id ) {
							printf( '<span><img src="%1$s" download="%1$s"></span>', wp_get_attachment_url( $file_id ) );
						} ?>
                    </li>
                    <li class="docpro-appointment-payment">
                        <p><strong><?php esc_html_e( 'Payment Status', 'docpro' ); ?></strong><span><?php echo esc_html( $booking->get_payment_details( 'status' ) ); ?></span></p>
                        <p><strong><?php esc_html_e( 'Payment Amount', 'docpro' ); ?></strong><span><?php echo esc_html( $booking->get_payment_details( 'amount' ) ); ?></span></p>
                        <p><strong><?php esc_html_e( 'Payment Method', 'docpro' ); ?></strong><span><?php echo esc_html( $booking->get_payment_details( 'method' ) ); ?></span></p>
                    </li>
                    <li class="docpro-appointment-status"><?php echo esc_html( $booking->get_status() ); ?></li>
                    <li class="docpro-appointment-action" data-do="accept" data-id="<?php echo esc_attr( $booking_id ); ?>"><i class="fas fa-check"></i><?php esc_html_e( 'Accept', 'docpro' ); ?></li>
                    <li class="docpro-appointment-action" data-do="cancel" data-id="<?php echo esc_attr( $booking_id ); ?>"><i class="fas fa-times"></i><?php esc_html_e( 'Cancel', 'docpro' ); ?></li>
                </ul>

            </div>
        </div>

	<?php endforeach; ?>
</div>
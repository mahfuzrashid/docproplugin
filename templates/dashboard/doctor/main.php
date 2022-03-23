<?php
/**
 * Dashboard - Doctor Main
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

$booking_ids  = $doctor->get_booking_ids();
$all_patients = $doctor->get_all_patients();

?>

<div class="feature-content">
    <div class="row clearfix">
        <div class="col-xl-4 col-lg-12 col-md-12 feature-block">
            <div class="feature-block-two">
                <div class="inner-box">
                    <div class="pattern">
                        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-79.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                        <div class="pattern-2" style="background-image: url(<?php printf( '%sassets/images/shape/shape-80.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                    </div>
                    <div class="icon-box"><i class="icon-Dashboard-1"></i></div>
                    <h3><?php echo esc_html( count( $all_patients ) ); ?></h3>
                    <h5><?php echo _n( 'Total patient', 'Total Patients', count( $all_patients ), 'docpro' ); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 feature-block">
            <div class="feature-block-two">
                <div class="inner-box">
                    <div class="pattern">
                        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-81.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                        <div class="pattern-2" style="background-image: url(<?php printf( '%sassets/images/shape/shape-82.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                    </div>
                    <div class="icon-box"><i class="icon-Dashboard-2"></i></div>
                    <h3><?php echo esc_html( $doctor->get_reviews_count() ); ?></h3>
                    <h5><?php echo _n( 'Total review', 'Total reviews', $doctor->get_reviews_count(), 'docpro' ); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12 feature-block">
            <div class="feature-block-two">
                <div class="inner-box">
                    <div class="pattern">
                        <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-83.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                        <div class="pattern-2" style="background-image: url(<?php printf( '%sassets/images/shape/shape-84.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                    </div>
                    <div class="icon-box"><i class="icon-Dashboard-3"></i></div>
                    <h3><?php echo esc_html( count( $booking_ids ) ); ?></h3>
                    <h5><?php echo _n( 'Total appointment', 'Total appointments', count( $booking_ids ), 'docpro' ); ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="doctors-appointment">
    <div class="title-box">
        <h3><?php esc_html_e( 'Patients Appointments', 'docpro' ); ?></h3>
    </div>
    <div class="doctors-list">
        <div class="table-outer">
            <table class="docpro-table doctors-table">
                <thead class="table-header">
                <tr>
                    <th><?php esc_html_e( 'Patient Name', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Date', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Service', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Amount', 'docpro' ); ?></th>
                </tr>
                </thead>
                <tbody>

				<?php foreach ( $booking_ids as $booking_id ) : $booking = docpro_get_booking( $booking_id ); ?>

                    <tr>
                        <td>
                            <div class="name-box">
								<?php printf( '<figure class="image"><img src="%s" alt="%s"></figure>', $booking->patient->get_avatar_url(), $booking->patient->display_name ); ?>
								<?php printf( '<h5>%s</h5>', $booking->patient->display_name ); ?>
                                <span class="designation">#<?php echo esc_html( $booking->id ); ?></span>
                            </div>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->date ); ?></p>
                            <span class="time"><?php echo esc_html( $booking->time ); ?></span>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->service_title ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( ucfirst( $booking->status ) ); ?></p>
                        </td>
                        <td>
							<?php printf( '<p>%s</p>', $booking->get_service_price_formatted() ); ?>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
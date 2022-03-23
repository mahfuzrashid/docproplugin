<?php
/**
 * Dashboard - Patient Main
 */

defined( 'ABSPATH' ) || exit;

global $patient;

$bookings    = $patient->get_booking_ids();
$fav_doctors = $patient->get_favourites( array( 'role' => 'doctor' ) );


?>
<div class="outer-container">

    <div class="feature-content">
        <div class="row clearfix">
            <div class="col-xl-4 col-lg-12 col-md-12 feature-block">
                <div class="feature-block-two">
                    <div class="inner-box">
                        <div class="pattern">
                            <div class="pattern-1" style="background-image: url(<?php printf( '%sassets/images/shape/shape-79.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                            <div class="pattern-2" style="background-image: url(<?php printf( '%sassets/images/shape/shape-80.png', DOCPRO_PLUGIN_URL ); ?>);"></div>
                        </div>
                        <div class="icon-box"><i class="icon-Dashboard-3"></i></div>
						<?php printf( '<h3>%s</h3>', count( $bookings ) ); ?>
                        <h5><?php esc_html_e( 'Total Appointments', 'docpro' ); ?></h5>
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
                        <div class="icon-box"><i class="fas fa-heart"></i></div>
	                    <?php printf( '<h3>%s</h3>', count( $fav_doctors ) ); ?>
                        <h5><?php esc_html_e( 'Total Favourites', 'docpro' ); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="doctors-appointment">
        <div class="title-box">
            <h3><?php esc_html_e( 'Doctors Appointments', 'docpro' ); ?></h3>
        </div>
        <div class="doctors-list">
            <div class="table-outer">
                <table class="doctors-table">
                    <thead class="table-header">
                    <tr>
                        <th><?php esc_html_e( 'Doctor Name', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Service', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Amount', 'docpro' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>

					<?php foreach ( $patient->get_booking_ids() as $booking_id ) : $booking = docpro_get_booking( $booking_id ); ?>
                        <tr>
                            <td>
                                <div class="name-box">
									<?php printf( '<figure class="image"><img src="%s" alt="%s"></figure>', $booking->doctor->get_avatar_url(), $booking->doctor->display_name ); ?>
									<?php printf( '<h5>%s</h5>', $booking->doctor->display_name ); ?>
									<?php printf( '<span class="designation">%s</span>', $booking->doctor->get_designation_html() ); ?>
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
                                <p><?php echo esc_html( $booking->service_price ); ?></p>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

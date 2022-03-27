<?php
/**
 * Dashboard - My Appointments
 */

defined( 'ABSPATH' ) || exit;

global $patient;


//update_post_meta( 148, '_files', array( 149, 150 ) );

?>
<div class="outer-container">
    <div class="doctors-appointment">
        <div class="title-box">
            <h3><?php esc_html_e( 'Doctors Appointments', 'docpro' ); ?></h3>
        </div>
        <div class="doctors-list">
            <div class="table-outer">
                <table class="doctors-table">
                    <thead class="table-header">
                    <tr>
                        <th></th>
                        <th><?php esc_html_e( 'Doctor Name', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Service', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Amount', 'docpro' ); ?></th>
                        <th><?php esc_html_e( 'Files', 'docpro' ); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

					<?php foreach ( $patient->get_booking_ids() as $booking_id ) : $booking = docpro_get_booking( $booking_id ); ?>
                        <tr>
                            <td>
                                <span class="docpro-remove-booking" data-id="<?php echo esc_attr( $booking_id ); ?>"><?php esc_html_e( 'Cancel', 'docpro' ); ?></span>
                            </td>
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
                            <td>
								<?php
								docpro()->pbSettings->generate_gallery( array(
									'id'           => "_files_$booking_id",
									'class'        => 'appointment-files',
									'sorting'      => false,
									'value'        => $booking->get_files(),
									'button_class' => 'appointment-files-button',
								) );
								?>
                                <div class="docpro-appointment-files-save" data-id="<?php echo esc_attr( $booking_id ); ?>"><?php esc_html_e( 'Save Changes', 'docpro' ); ?></div>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

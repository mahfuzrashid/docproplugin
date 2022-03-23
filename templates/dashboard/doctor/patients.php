<?php
/**
 * Dashboard - Doctor Main
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>

<div class="doctors-appointment my-patients">
    <div class="title-box">
        <h3>Patients List</h3>
    </div>
    <div class="doctors-list">
        <div class="table-outer">
            <table class="docpro-table doctors-table">
                <thead class="table-header">
                <tr>
                    <th><?php esc_html_e( 'Patient Name', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Sex', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Address', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Mobile', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Age', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Blood Group', 'docpro' ); ?></th>
                    <th><?php esc_html_e( 'Service Booked', 'docpro' ); ?></th>
                </tr>
                </thead>
                <tbody>

				<?php foreach ( $doctor->get_booking_ids() as $booking_id ) : $booking = docpro_get_booking( $booking_id ); ?>
                    <tr>
                        <td>
                            <div class="name-box">
								<?php printf( '<figure class="image"><img src="%s" alt="%s"></figure>', $booking->patient->get_avatar_url(), $booking->patient->display_name ); ?>
								<?php printf( '<h5><a href="%s">%s</a></h5>', $booking->patient->get_profile_permalink(), $booking->patient->display_name ); ?>
                            </div>
                        </td>
                        <td>
                            <p><?php echo esc_html( ucwords( $booking->patient->gender ) ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->patient->address ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->patient->phone ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->patient->age ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->patient->blood_group ); ?></p>
                        </td>
                        <td>
                            <p><?php echo esc_html( $booking->service_title ); ?></p>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

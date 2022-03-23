<?php
/**
 * Doctor Details - Nav Item Overview Section - Services
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>

<div class="accordion-box">
    <h3><?php esc_html_e( 'Offered Services', 'docpro' ); ?></h3>

    <div class="title-box">
        <h6><?php printf( '%s<span>%s</span>', esc_html__( 'Service', 'docpro' ), esc_html__( 'Price', 'docpro' ) ); ?></h6>
    </div>

	<?php if ( ! empty( $doctor->services ) ) : ?>
        <ul class="accordion-inner">
			<?php foreach ( $doctor->services as $index => $service ) : ?>
                <li class="accordion block <?php docpro_active_class( 0, $index, 'active-block' ); ?>">
                    <div class="acc-btn <?php docpro_active_class( 0, $index ); ?>">
                        <div class="icon-outer"></div>
                        <h6>
							<?php printf( '%s<span>%s</span>',
								docpro()->get_args_option( 'title', '', $service ),
								docpro()->get_args_option( 'price', '', $service )
							); ?>
                        </h6>
                    </div>
                    <div class="acc-content <?php docpro_active_class( 0, $index, 'current' ); ?>">
                        <div class="text">
							<?php printf( '<p>%s</p>', docpro()->get_args_option( 'details', '', $service ) ); ?>
                        </div>
                    </div>
                </li>
			<?php endforeach; ?>
        </ul>
	<?php endif; ?>
</div>
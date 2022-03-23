<?php
/**
 * Doctor Details - Nav Item - Experiences
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $doctor;

?>

<div class="tab <?php echo esc_attr( $active_class ); ?>" id="experiences">
    <div class="experience-box">
        <div class="text">
            <h3><?php esc_html_e( 'Professional Experience', 'docpro' ); ?></h3>

			<?php if ( ! empty( $doctor->experiences ) ) : ?>
                <ul class="experience-list list clearfix">
					<?php foreach ( $doctor->experiences as $experience ) : ?>
						<?php
						$start_date = docpro()->get_args_option( 'started', '', $experience );
						$end_date   = docpro()->get_args_option( 'end', '', $experience );

						printf( '<li>%s <span>(%s)</span> <p>%s <span>%s</span></p></li>',
							docpro()->get_args_option( 'institution', '', $experience ),
							docpro()->get_args_option( 'department', '', $experience ),
							docpro()->get_args_option( 'position', '', $experience ),
							docpro_date_range( $start_date, $end_date ) );
						?>
					<?php endforeach; ?>
                </ul>
			<?php endif; ?>

            <h3><?php esc_html_e( 'Key Skills', 'docpro' ); ?></h3>
			<?php if ( ! empty( $doctor->skills ) ) : ?>
                <ul class="skills-list list clearfix">
					<?php foreach ( $doctor->skills as $skill ) : ?>
                        <li>
							<?php printf( esc_html__( 'Expert in %s', 'docpro' ), docpro()->get_args_option( 'name', '', $skill ) ); ?>
                        </li>
					<?php endforeach; ?>
                </ul>
			<?php endif; ?>
        </div>
    </div>
</div>
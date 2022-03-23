<?php
/**
 * Doctor Details - Nav Item - Overview
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="tab <?php echo esc_attr( $active_class ); ?>" id="overview">
    <div class="inner-box">
        <div class="text">
			<?php
			/**
			 * Section - About
			 */
			docpro_get_template_part( 'doctor/nav/overview/sec', 'about' ); ?>

			<?php
			/**
			 * Section - Specialities
			 */
			docpro_get_template_part( 'doctor/nav/overview/sec', 'specialities' ); ?>

			<?php
			/**
			 * Section - Educations
			 */
			docpro_get_template_part( 'doctor/nav/overview/sec', 'educations' ); ?>
        </div>


		<?php
		/**
		 * Section - Services
		 */
		docpro_get_template_part( 'doctor/nav/overview/sec', 'services' ); ?>

		<?php
		/**
		 * Section - Services
		 */
		docpro_get_template_part( 'doctor/nav/overview/sec', 'awards' ); ?>
    </div>
</div>
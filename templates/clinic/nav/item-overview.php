<?php
/**
 * Clinic Details - Nav Item - Overview
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
			docpro_get_template_part( 'clinic/nav/overview/sec', 'about' ); ?>

			<?php
			/**
			 * Section - Specifications
			 */
			docpro_get_template_part( 'clinic/nav/overview/sec', 'specifications' ); ?>
        </div>


		<?php
		/**
		 * Section - Services
		 */
		docpro_get_template_part( 'clinic/nav/overview/sec', 'services' ); ?>

		<?php
		/**
		 * Section - Awards
		 */
		docpro_get_template_part( 'clinic/nav/overview/sec', 'awards' ); ?>

	    <?php
	    /**
	     * Section - gallery
	     */
	    docpro_get_template_part( 'clinic/nav/overview/sec', 'gallery' ); ?>
    </div>
</div>
<?php
/**
 * Clinic Archive
 */

defined( 'ABSPATH' ) || exit;

global $docpro_query, $wp;

$docpro_query = docpro_get_archive_query(
	array(
		'role'          => 'clinic',
		'fields'        => 'ID',
		'class_list'    => 'clinic-block-one',
		'class_content' => isset( $view ) && $view === 'large-map' ? 'col-lg-6' : 'col-lg-8',
		'class_sidebar' => isset( $view ) && $view === 'large-map' ? 'col-lg-6' : 'col-lg-4',
	)
);

?>
    <section class="clinic-section clinics-page-section">
        <form action="<?php echo esc_url( site_url( $wp->request ) ); ?>" method="get" id="archive-search-form">
			<?php
			/**
			 * Before archive content action
			 *
			 * @see docpro_archive_search_bar()
			 * @see docpro_archive_header()
			 */
			do_action( 'docpro_before_archive_content', $args ); ?>
        </form>

        <div class="row clearfix">
            <div class="<?php echo esc_attr( $docpro_query->get( 'class_content' ) ); ?> col-md-12 col-sm-12 content-side">
				<?php

				if ( $docpro_query->get_total() > 0 ) {

					/**
					 * Clinic - Items
					 *
					 * @action docpro_before_archive_items
					 */
					do_action( 'docpro_before_archive_items', $args );

					docpro_get_template( 'clinic-archive/items.php' );
				} else {
					docpro_get_template( 'doctor-archive/no-item.php' );
				}

				?>

				<?php
				/**
				 * Clinic - Pagination
				 *
				 * @action docpro_before_archive_pagination
				 */
				do_action( 'docpro_before_archive_pagination', $args );

				docpro_get_template( 'clinic-archive/pagination.php' ); ?>
            </div>

            <div class="<?php echo esc_attr( $docpro_query->get( 'class_sidebar' ) ); ?> col-md-12 col-sm-12 sidebar-side">
				<?php
				/**
				 * Clinic - Sidebar
				 *
				 * @action docpro_before_archive_sidebar
				 */
				do_action( 'docpro_before_archive_sidebar', $args );

				docpro_get_template( 'clinic-archive/sidebar.php' ); ?>
            </div>
        </div>
    </section>

<?php

wp_reset_query();
<?php
/**
 * Template Functions with Function Hooks
 */

add_action( 'docpro_before_archive_content', 'docpro_archive_search_bar', 10, 1 );
add_action( 'docpro_before_archive_content', 'docpro_archive_header', 20, 1 );


if ( ! function_exists( 'docpro_archive_header' ) ) {
	function docpro_archive_header( $args ) {
		docpro_get_template( 'archive-header.php', $args );
	}
}

if ( ! function_exists( 'docpro_archive_search_bar' ) ) {
	function docpro_archive_search_bar( $args ) {
		docpro_get_template( 'archive-search-bar.php', $args );
	}
}




<?php
/**
 * All Functions
 *
 * @author Pluginbazar
 */


if ( ! function_exists( 'docpro' ) ) {
	/**
	 * Return global $docpro
	 *
	 * @return DOCPRO_Functions
	 */
	function docpro() {
		global $docpro;

		if ( empty( $docpro ) ) {
			$docpro = new DOCPRO_Functions();
		}

		return $docpro;
	}
}


if ( ! function_exists( 'docpro_generate_classes' ) ) {
	/**
	 * Generate and return classes
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function docpro_generate_classes( $classes = '' ) {

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		return implode( " ", apply_filters( 'docpro_generate_classes', array_filter( $classes ) ) );
	}
}


if ( ! function_exists( 'docpro_single_post_class' ) ) {
	/**
	 * Return single post classes
	 *
	 * @param string $classes
	 */
	function docpro_single_post_class( $classes = '' ) {

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		$classes[] = sprintf( '%s-single', get_post_type() );

		printf( 'class="%s"', docpro_generate_classes( $classes ) );
	}
}


if ( ! function_exists( 'docpro_get_template_part' ) ) {
	/**
	 * Get Template Part
	 *
	 * @param $slug
	 * @param string $name
	 * @param array $args
	 */
	function docpro_get_template_part( $slug, $name = '', $args = array() ) {

		$template   = '';
		$plugin_dir = DOCPRO_PLUGIN_DIR;

		/**
		 * Locate template
		 */
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				"docpro/{$slug}-{$name}.php"
			) );
		}

		/**
		 * Search for Template in Plugin
		 *
		 * @in Plugin
		 */
		if ( ! $template && $name && file_exists( untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php";
		}


		/**
		 * Search for Template in Theme
		 *
		 * @in Theme
		 */
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "docpro/{$slug}.php" ), false, true, $args );
		}


		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @filter docpro_filters_get_template_part
		 */
		$template = apply_filters( 'docpro_filters_get_template_part', $template, $slug, $name );


		if ( is_array( $args ) && ! empty( $args ) ) {
			global $wp_query;
			foreach ( $args as $key => $val ) {
				$wp_query->set( $key, $val );
			}
		}


		if ( $template ) {
			load_template( $template, false, $args );
		}
	}
}


if ( ! function_exists( 'docpro_get_template' ) ) {
	/**
	 * Get Template
	 *
	 * @param $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 */
	function docpro_get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $main_template = false ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = docpro_locate_template( $template_name, $template_path, $default_path, $main_template );

		if ( ! file_exists( $located ) ) {
			return;
		}

		$located = apply_filters( 'docpro_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'docpro_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'docpro_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'docpro_locate_template' ) ) {
	/**
	 *  Locate template
	 *
	 * @param $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return mixed|void
	 */
	function docpro_locate_template( $template_name, $template_path = '', $default_path = '', $main_template = false ) {

		$plugin_dir = DOCPRO_PLUGIN_DIR;

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'docpro/';
		}

//		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'DOCPROP_PLUGIN_DIR' ) ) {
//			$plugin_dir = $main_template ? DOCPRO_PLUGIN_DIR : DOCPROP_PLUGIN_DIR;
//		}
//		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-survey' ) !== false && defined( 'DOCPROS_PLUGIN_DIR' ) ) {
//			$plugin_dir = $main_template ? DOCPRO_PLUGIN_DIR : DOCPROS_PLUGIN_DIR;
//		}
//		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-quiz' ) !== false && defined( 'DOCPROQUIZ_PLUGIN_DIR' ) ) {
//			$plugin_dir = $main_template ? DOCPRO_PLUGIN_DIR : DOCPROQUIZ_PLUGIN_DIR;
//		}


		/**
		 * Template default path from Plugin
		 */
		if ( ! $default_path ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/templates/';
		}

		/**
		 * Look within passed path within the theme - this is priority.
		 */
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		/**
		 * Get default template
		 */
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Return what we found with allowing 3rd party to override
		 *
		 * @filter docpro_filters_locate_template
		 */
		return apply_filters( 'docpro_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'docpro_pagination' ) ) {
	/**
	 * Return Pagination HTML Content
	 *
	 * @param bool $query_object
	 * @param array $args
	 *
	 * @return array|string|void
	 */
	function docpro_pagination( $query_object = false, $args = array() ) {

		global $wp_query;

		$previous_query = $wp_query;

		if ( $query_object ) {
			$wp_query = $query_object;
		}

		$paged = max( 1, ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1 );

		$defaults = array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $paged,
			'total'     => $wp_query->max_num_pages,
			'prev_text' => esc_html__( 'Previous', 'docpro' ),
			'next_text' => esc_html__( 'Next', 'docpro' ),
		);

		$args           = apply_filters( 'docpro_filters_pagination', array_merge( $defaults, $args ) );
		$paginate_links = paginate_links( $args );

		$wp_query = $previous_query;

		return $paginate_links;
	}
}


if ( ! function_exists( 'docpro_get_content_nav_tabs' ) ) {
	/**
	 * Return content nav items
	 *
	 * @param string $context
	 *
	 * @return mixed|void
	 */
	function docpro_get_content_nav_tabs() {

		$nav_items = array(
			'overview'    => esc_html__( 'Overview', 'docpro' ),
			'experiences' => esc_html__( 'Experiences', 'docpro' ),
			'reviews'     => esc_html__( 'Reviews', 'docpro' ),
		);

		if ( docpro()->is_page( 'clinic' ) ) {
			unset( $nav_items['experiences'] );
			$nav_items['doctors'] = esc_html__( 'Onboard Doctors', 'docpro' );
		}

		$nav_items['locations'] = esc_html__( 'Locations', 'docpro' );

		return apply_filters( "docpro_filters_content_nav_tabs", $nav_items );
	}
}


if ( ! function_exists( 'docpro_get_profile' ) ) {
	/**
	 * Docpro return profile
	 *
	 * @param string $profile_for
	 * @param int $id
	 *
	 * @return DOCPRO_User_base|DOCPRO_User_doctor|DOCPRO_User_patient|DOCPRO_User_clinic
	 */
	function docpro_get_profile( $profile_for = '', $id = 0 ) {

		$username = get_query_var( $profile_for );
		$username = explode( '/', $username );
		$username = isset( $username[0] ) ? $username[0] : '';

		if ( $id !== 0 && ! empty( $id ) ) {
			$all_roles = get_userdata( $id )->roles;
			if ( empty( $profile_for ) && is_array( $all_roles ) ) {
				$profile_for = reset( $all_roles );
			}
		}

		switch ( $profile_for ) {
			case 'doctor':
				return new DOCPRO_User_doctor( $id, $username );

			case 'patient':
				return new DOCPRO_User_patient( $id, $username );

			case 'clinic' :
				return new DOCPRO_User_clinic( $id, $username );

			default:
				return new DOCPRO_User_base( $id, $username );
		}
	}
}


if ( ! function_exists( 'docpro_get_location' ) ) {
	/**
	 * Return location class data
	 *
	 * @param int $location_id
	 *
	 * @return DOCPRO_Location
	 */
	function docpro_get_location( $location_id = 0 ) {
		return new DOCPRO_Location( $location_id );
	}
}


if ( ! function_exists( 'docpro_array_map' ) ) {
	function docpro_array_map( $arr_key, $arr = array() ) {
		$arr = ! is_array( $arr ) ? (array) $arr : $arr;

		return array_map( function ( $degree ) use ( $arr_key ) {
			return docpro()->get_args_option( $arr_key, '', $degree );
		}, $arr );
	}
}


if ( ! function_exists( 'docpro_active_class' ) ) {
	/**
	 * Return active class
	 *
	 * @param $helper
	 * @param $current
	 * @param string $class_name
	 * @param bool $echo
	 *
	 * @return string
	 */
	function docpro_active_class( $helper, $current, $class_name = 'active', $echo = true ) {
		$result = (string) $helper === (string) $current ? $class_name : '';

		if ( $echo ) {
			echo esc_attr( $result );
		}

		return $result;
	}
}


if ( ! function_exists( 'docpro_date_range' ) ) {
	/**
	 * @param $start
	 * @param string $end
	 * @param string $format
	 * @param string $separator
	 * @param false $echo
	 *
	 * @return mixed|void
	 */
	function docpro_date_range( $start, $end = '', $format = 'M Y', $separator = '-', $echo = false ) {
		$parts[]    = date( $format, strtotime( $start ) );
		$end        = empty( $end ) ? '' : date( $format, strtotime( $end ) );
		$parts[]    = $separator;
		$parts[]    = empty( $end ) ? esc_html__( 'Continuing', 'docpro' ) : $end;
		$date_range = apply_filters( 'docpro_filters_date_range', implode( ' ', $parts ) );

		if ( $echo ) {
			echo esc_html( $date_range );
		} else {
			return $date_range;
		}
	}
}


if ( ! function_exists( 'docpro_get_archive_query' ) ) {
	/**
	 * Return array of users with doctors role
	 *
	 * @param array $args
	 *
	 * @return WP_User_Query
	 */
	function docpro_get_archive_query( $args = array() ) {

		global $url_data;

		$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$offset     = docpro()->get_users_per_page( $args ) * ( $paged - 1 );
		$defaults   = array(
			'count_total' => true,
			'orderby'     => 'display_name',
			'number'      => docpro()->get_users_per_page( $args ),
			'offset'      => $offset,
			'meta_query'  => array(),
		);
		$meta_query = docpro()->get_args_option( 'meta_query', array(), $defaults );
		$meta_query = is_array( $meta_query ) ? $meta_query : array();

		if ( ! empty( $searched_name = docpro()->get_args_option( 'login', '', $url_data ) ) ) {
			$defaults['search']         = esc_attr( $searched_name );
			$defaults['search_columns'] = array( 'user_login', 'user_nicename', 'user_email', 'user_url', 'display_name' );
		}

		docpro_check_meta_query( $meta_query );

		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = array( 'relation' => 'AND' ) + $meta_query;
		}

		// Check sorting
		if ( ! empty( $sort_by = docpro()->get_args_option( 'sort', '', $url_data ) ) ) {

			if ( $sort_by === 'name_z_a' ) {
				$order = 'DESC';
			} else {
				$order = 'ASC';
			}

			$args['orderby'] = 'display_name';
			$args['order']   = $order;
		}

		return new WP_User_Query( apply_filters( 'docpro_filters_main_users_query', wp_parse_args( $args, $defaults ) ) );
	}
}

if ( ! function_exists( 'docpro_check_meta_query' ) ) {
	/**
	 * Check meta query and add it to main query
	 *
	 * @param $meta_query
	 */
	function docpro_check_meta_query( &$meta_query ) {

		global $url_data;

		// Check for name search
		if ( ! empty( $searched_clinic = docpro()->get_args_option( 'n', '', $url_data ) ) ) {
			$meta_query[] = array(
				'relation' => 'OR',
				array(
					'key'     => 'first_name',
					'value'   => $searched_clinic,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'last_name',
					'value'   => $searched_clinic,
					'compare' => 'LIKE',
				),
			);
		}

		// Check for department search
		if ( ! empty( $selected_dept = docpro()->get_args_option( 'dept', '', $url_data ) ) ) {
			$meta_query[] = array(
				'key'     => '_department',
				'value'   => sprintf( '%s', $selected_dept ),
				'compare' => '=',
			);
		}

		// Check for location search
		if ( ! empty( $selected_loc = docpro()->get_args_option( 'loc', '', $url_data ) ) ) {
			$meta_query[] = array(
				'key'     => '_locations',
				'value'   => sprintf( '"%s"', $selected_loc ),
				'compare' => 'LIKE',
			);
		}


		// Check for availability search
		if ( ! empty( $availability_status = docpro()->get_args_option( 'status', '', $url_data ) ) ) {
			$meta_query[] = array(
				'key'     => '_availability',
				'value'   => 1,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		}

	}
}


if ( ! function_exists( 'docpro_get_archive_items' ) ) {
	/**
	 * Return archive items based on view and context
	 *
	 * @param string $view list|grid
	 * @param string $context doctor | clinic | patient
	 *
	 * @return false|string
	 */
	function docpro_get_archive_items( $view = 'list', $context = 'doctor' ) {

		global $docpro_query;

		ob_start();

		foreach ( $docpro_query->get_results() as $user_id ) {

			global $doctor, $patient, $clinic;

			if ( $context == 'doctor' ) {
				$doctor = docpro_get_profile( 'doctor', $user_id );
			} else if ( $context == 'patient' ) {
				$patient = docpro_get_profile( 'patient', $user_id );
			} else if ( $context == 'clinic' ) {
				$clinic = docpro_get_profile( 'clinic', $user_id );
			}

			docpro_get_template_part( $context . '-archive/item-view', $view );
		}

		return ob_get_clean();
	}
}


if ( ! function_exists( 'docpro_paginate_links' ) ) {
	/**
	 * Render or return paginate link
	 *
	 * @param array $args
	 * @param null $query_obj
	 * @param bool $is_echo
	 *
	 * @return array|bool|string|void
	 */
	function docpro_paginate_links( $args = array(), $query_obj = null, $is_echo = true ) {

		global $docpro_query;

		$query_obj   = ! $query_obj ? $docpro_query : $query_obj;
		$paged       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$total_pages = ceil( $query_obj->get_total() / docpro()->get_users_per_page( $args ) );
		$defaults    = array(
			'base'      => str_replace( 999999, '%#%', esc_url( get_pagenum_link( 999999 ) ) ),
			'format'    => '&p=%#%',
			'prev_text' => esc_attr( '<' ),
			'next_text' => esc_attr( '>' ),
			'total'     => $total_pages,
			'current'   => $paged,
			'end_size'  => 1,
			'mid_size'  => 5,
		);
		$args        = wp_parse_args( $args, $defaults );

		if ( $is_echo ) {
			printf( '%s', paginate_links( $args ) );
		} else {
			return paginate_links( $args );
		}

		return true;
	}
}


if ( ! function_exists( 'docpro_is_user' ) ) {
	/**
	 * Check if a user is that type or not
	 *
	 * @param string $type
	 * @param int $user_id
	 *
	 * @return bool|DOCPRO_User_base|DOCPRO_User_clinic|DOCPRO_User_doctor|DOCPRO_User_patient
	 */
	function docpro_is_user( $type = '', $user_id = 0 ) {

		$user_id = ! $user_id || $user_id === 0 ? get_current_user_id() : $user_id;
		$user    = docpro_get_profile( $type, $user_id );

		if ( $user->type == $type ) {
			return $user;
		}

		return false;
	}
}


if ( ! function_exists( 'docpro_calculate_map_center' ) ) {
	/**
	 * Calculate and return center of some points in Google Map
	 *
	 * @param array $locations
	 *
	 * @return mixed|void
	 */
	function docpro_calculate_map_center( $locations = array() ) {
		if ( ! is_array( $locations ) || empty( $locations ) ) {
			return '';
		}

		return apply_filters( 'docpro_filters_calculate_map_center', $locations[ array_rand( $locations ) ], $locations );
	}
}


if ( ! function_exists( 'docpro_render_form_hidden_fields' ) ) {
	/**
	 * Render hidden fields for arguments
	 *
	 * @param array $args
	 */
	function docpro_render_form_hidden_fields( $args = array() ) {
		$args = ! is_array( $args ) || empty( $args ) ? wp_unslash( $_GET ) : $args;
		foreach ( $args as $k => $v ) {
			printf( '<input type="hidden" name="%s" value="%s">', $k, $v );
		}
	}
}


if ( ! function_exists( 'docpro_render_rating' ) ) {
	/**
	 * Render rating html
	 *
	 * @param int $rating
	 * @param array $args
	 * @param bool $echo
	 *
	 * @return mixed|void
	 */
	function docpro_render_rating( $rating = 0, $args = array(), $echo = true ) {

		$rating_lines  = array();
		$maximum       = (int) docpro()->get_args_option( 'maximum', 5, $args );
		$display_count = (bool) docpro()->get_args_option( 'display_count', false, $args );
		$total_reviews = (int) docpro()->get_args_option( 'total_reviews', false, $args );
		$rating        = ceil( $rating );

		for ( $index = 1; $index <= $maximum; ++ $index ) {
			if ( is_admin() ) {
				$rating_lines[] = sprintf( '<li><span class="dashicons dashicons-star-%s"></span></li>', $index <= $rating ? 'filled' : 'empty' );
			} else {
				$rating_lines[] = sprintf( '<li class="%s"><i class="icon-Star"></i></li>', $index <= $rating ? '' : 'light' );
			}
		}

		if ( $display_count ) {
			$rating_lines[] = sprintf( '<li>(%s)</li>', $total_reviews );
		}

		$output = sprintf( '<ul class="docpro-rating rating clearfix">%s</ul>', implode( '', $rating_lines ) );

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}


if ( ! function_exists( 'docpro_add_service_to_cart' ) ) {
	/**
	 * Add service to cart as product of WooCommerce
	 *
	 * @param array $posted_data
	 * @param string $doctor
	 *
	 * @return bool|string
	 */
	function docpro_add_service_to_cart( $posted_data = array(), $doctor = '' ) {

		if ( ! $doctor || empty( $doctor ) ) {
			global $doctor;
		}

		if ( ! function_exists( 'WC' ) || ! is_array( $posted_data ) || ! $doctor instanceof DOCPRO_User_doctor ) {
			return false;
		}

		if ( ! is_array( $service = $doctor->get_service_details( docpro()->get_args_option( 'service', '', $posted_data ) ) ) || empty( $service ) ) {
			return false;
		}

		$_product = new WC_Product_Simple();
		$_product->set_name( docpro()->get_args_option( 'title', '', $service ) );
		$_product->set_regular_price( docpro()->get_args_option( 'price', '', $service ) );
		$_product->set_status( 'publish' );

		$product_id      = $_product->save();
		$product_cart_id = WC()->cart->generate_cart_id( $product_id );

		if ( ! WC()->cart->find_product_in_cart( $product_cart_id ) ) {
			try {
				update_post_meta( $product_id, '_service', $service );
				update_post_meta( $product_id, '_doctor_id', $doctor->ID );
				update_post_meta( $product_id, '_date', docpro()->get_args_option( 'date', '', $posted_data ) );
				update_post_meta( $product_id, '_time', docpro()->get_args_option( 'time', '', $posted_data ) );

				return WC()->cart->add_to_cart( $product_id, 1, 0, array(), $service );
			} catch ( Exception $e ) {
				return false;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'docpro_render_likebox' ) ) {
	/**
	 * Render likebox / favourite icons
	 *
	 * @param int $user
	 */
	function docpro_render_likebox( $user = 0 ) {

		if ( is_int( $user ) ) {
			$user = docpro_get_profile( '', $user );
		}

		$_followers    = $user->get_meta( '_followers', array() );
		$liked_class   = is_array( $_followers ) && in_array( get_current_user_id(), $_followers ) ? 'liked' : 'unliked';
		$tt_hint_label = is_user_logged_in() ? '' : esc_html__( 'You must login to save', 'docpro' );

		printf( '<div class=" docpro-likebox tt--top like-box %s"   aria-label="%s"  data-entity="%s" ><span class="far fa-heart"></span></div>', $liked_class, $tt_hint_label, $user->ID );
	}
}


if ( ! function_exists( 'docpro_dashboard_nav_links' ) ) {
	/**
	 * @param array $nav_items
	 * @param bool $echo
	 *
	 * @return false|string|void
	 */
	function docpro_dashboard_nav_links( $nav_items = array(), $echo = true ) {

		global $wp;

		$dashboard_page = docpro()->get_option( 'docpro_dashboard_page' );
		$dashboard_page = get_the_permalink( $dashboard_page );
		$current_nav    = get_query_var( 'content' );

		ob_start();

		foreach ( $nav_items as $nav => $label ) {

			$nav_link   = str_replace( '//content', '/content', sprintf( '%s/content/%s', $dashboard_page, $nav ) );
			$nav_link   = $nav == 'logout' ? wp_logout_url( site_url( $wp->request ) ) : $nav_link;
			$is_current = $current_nav == $nav ? 'current' : '';

			printf( '<li><a href="%s" class="%s">%s</a></li>', $nav_link, $is_current, $label );
		}

		if ( $echo ) {
			printf( '%s', ob_get_clean() );

			return;
		}

		return ob_get_clean();
	}
}


if ( ! function_exists( 'docpro_render_dashboard_content' ) ) {
	/**
	 * Render dashboard content
	 *
	 * @param string $profile_for
	 */
	function docpro_render_dashboard_content( $profile_for = '' ) {

		$template_name = get_query_var( 'content', 'main' );
		$template_name = empty( $template_name ) ? 'main' : $template_name;

		// left panel
		ob_start();
		docpro_get_template( sprintf( 'dashboard/%s/left-panel.php', $profile_for ) );
		printf( '<div class="left-panel">%s</div>', ob_get_clean() );

		// right panel
		ob_start();
		docpro_get_template( sprintf( 'dashboard/%s/%s.php', $profile_for, $template_name ) );
		printf( '<div class="right-panel"><div class="content-container">%s</div></div>', ob_get_clean() );
	}
}


if ( ! function_exists( 'docpro_get_booking' ) ) {
	/**
	 * Return booking object
	 *
	 * @param int $booking_id
	 *
	 * @return DOCPRO_Booking|false
	 */
	function docpro_get_booking( $booking_id = 0 ) {

		if ( ! $booking_id || empty( $booking_id ) || $booking_id === 0 ) {
			return false;
		}

		return new DOCPRO_Booking( $booking_id );
	}
}


if ( ! function_exists( 'docpro_generate_username' ) ) {
	/**
	 * Return username
	 *
	 * @param $username
	 *
	 * @return false|mixed|string
	 */
	function docpro_generate_username( $username ) {

		$username = sanitize_title( $username );

		static $i;
		if ( null === $i ) {
			$i = 1;
		} else {
			$i ++;
		}
		if ( ! username_exists( $username ) ) {
			return $username;
		}
		$new_username = sprintf( '%s%s', $username, $i );
		if ( ! username_exists( $new_username ) ) {
			return $new_username;
		} else {
			return call_user_func( __FUNCTION__, $username );
		}
	}
}


if ( ! function_exists( 'docpro_get_sharer_link' ) ) {
	/**
	 * Return sharer link for platform id
	 *
	 * @param $platform_id
	 *
	 * @return string
	 */
	function docpro_get_sharer_link( $platform_id ) {

		global $wp;

		$platforms = array(
			'facebook' => 'https://www.facebook.com/sharer/sharer.php?u',
			'twitter'  => 'https://twitter.com/intent/tweet?url'
		);

		return sprintf( '%s=%s', docpro()->get_args_option( $platform_id, '', $platforms ), site_url( $wp->request ) );
	}
}


if ( ! function_exists( 'docpro_get_education_fields' ) ) {
	/**
	 * Return education fields html
	 *
	 * @param array $education
	 *
	 * @return string
	 */
	function docpro_get_education_fields( $education = array() ) {

		$unique_id = docpro()->get_args_option( 'unique_id', time(), $education );
		$fields    = array(
			'degree'    => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="educations[%s][degree]" value="%s" required></div>',
				esc_html__( 'Degree', 'docpro' ), $unique_id, docpro()->get_args_option( 'degree', '', $education )
			),
			'institute' => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="educations[%s][institute]" value="%s" required></div>',
				esc_html__( 'Institute', 'docpro' ), $unique_id, docpro()->get_args_option( 'institute', '', $education )
			),
			'year'      => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="educations[%s][year]" value="%s" required></div>',
				esc_html__( 'Passing Year', 'docpro' ), $unique_id, docpro()->get_args_option( 'year', '', $education )
			),
		);
		$fields    = apply_filters( 'docpro_education_fields', $fields, $education );

		return sprintf( '<div class="row clearfix">%s</div>', implode( '', $fields ) );
	}
}


if ( ! function_exists( 'docpro_get_service_fields' ) ) {
	/**
	 * Return service fields html
	 *
	 * @param array $service
	 *
	 * @return string
	 */
	function docpro_get_service_fields( $service = array() ) {

		$unique_id = docpro()->get_args_option( 'unique_id', time(), $service );
		$fields    = array(
			'id'      => sprintf( '<input type="hidden" name="services[%s][id]" value="%s">', $unique_id, docpro()->get_args_option( 'id', rand( 10000, 99999 ), $service ) ),
			'title'   => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="services[%s][title]" value="%s" required></div>',
				esc_html__( 'Title', 'docpro' ), $unique_id, docpro()->get_args_option( 'title', '', $service )
			),
			'details' => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="services[%s][details]" value="%s" required></div>',
				esc_html__( 'Details', 'docpro' ), $unique_id, docpro()->get_args_option( 'details', '', $service )
			),
			'price'   => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="services[%s][price]" value="%s" required></div>',
				esc_html__( 'Price', 'docpro' ), $unique_id, docpro()->get_args_option( 'price', '', $service )
			),
		);
		$fields    = apply_filters( 'docpro_service_fields', $fields, $service );

		return sprintf( '<div class="row clearfix">%s</div>', implode( '', $fields ) );
	}
}


if ( ! function_exists( 'docpro_get_experience_fields' ) ) {
	/**
	 * Return experience fields html
	 *
	 * @param array $experience
	 *
	 * @return string
	 */
	function docpro_get_experience_fields( $experience = array() ) {

		$unique_id = docpro()->get_args_option( 'unique_id', time(), $experience );

		ob_start();
		docpro()->pbSettings->generate_datepicker( array(
			'id'            => sprintf( 'services[%s][started]', $unique_id ),
			'required'      => true,
			'type'          => 'datepicker',
			'default'       => docpro()->get_args_option( 'started', '', $experience ),
			'placeholder'   => esc_html( '22-12-2020' ),
			'field_options' => array(
				'dateFormat' => esc_attr( 'dd-mm-yy' ),
			),
		) );
		$html_started = ob_get_clean();

		ob_start();
		docpro()->pbSettings->generate_datepicker( array(
			'id'            => sprintf( 'experiences[%s][end]', $unique_id ),
			'required'      => true,
			'type'          => 'datepicker',
			'default'       => docpro()->get_args_option( 'end', '', $experience ),
			'placeholder'   => esc_html( '22-12-2020' ),
			'field_options' => array(
				'dateFormat' => esc_attr( 'dd-mm-yy' ),
			),
		) );
		$html_end = ob_get_clean();


		$fields = array(
			'institution' => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="experiences[%s][institution]" value="%s" required></div>',
				esc_html__( 'Institution', 'docpro' ), $unique_id, docpro()->get_args_option( 'institution', '', $experience )
			),
			'position'    => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="experiences[%s][position]" value="%s" required></div>',
				esc_html__( 'Position', 'docpro' ), $unique_id, docpro()->get_args_option( 'position', '', $experience )
			),
			'department'  => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="experiences[%s][department]" value="%s" required></div>',
				esc_html__( 'Department', 'docpro' ), $unique_id, docpro()->get_args_option( 'department', '', $experience )
			),
			'started'     => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label>%s</div>',
				esc_html__( 'Start date', 'docpro' ), $html_started
			),
			'end'         => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label>%s</div>',
				esc_html__( 'End date', 'docpro' ), $html_end
			),
		);
		$fields = apply_filters( 'docpro_education_fields', $fields, $experience );

		return sprintf( '<div class="row clearfix">%s</div>', implode( '', $fields ) );
	}
}


if ( ! function_exists( 'docpro_get_specification_fields' ) ) {
	/**
	 * Add specification field
	 *
	 * @param $specification
	 *
	 * @return string
	 */
	function docpro_get_specification_fields( $specification ) {

		$unique_id = docpro()->get_args_option( 'unique_id', time(), $specification );

		$fields = array(
			'name' => sprintf( '<div class="col-lg-4 col-md-6 col-sm-12 form-group"><label>%s</label><input type="text" name="specification[%s][name]" value="%s" required></div>',
				esc_html__( 'Name', 'docpro' ), $unique_id, docpro()->get_args_option( 'name', '', $specification )
			),
		);
		$fields = apply_filters( 'docpro_specification_fields', $fields, $specification );

		return sprintf( '<div class="row clearfix">%s</div>', implode( '', $fields ) );
	}
}












//add_action( 'wp_footer', function () {
//	do_action( 'woocommerce_new_order', 129 );
//} );
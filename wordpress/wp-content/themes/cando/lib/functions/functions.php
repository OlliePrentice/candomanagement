<?php
/**
 * For those misfit functions, with no other place to go
 */


/**
 * Return a code appropriate version of the theme name
 *
 * @return string
 */
function gft_theme_handle() {

	return sanitize_title_with_dashes( CHILD_THEME_NAME );

}


/**
 * Takes a string that is to be used as an ID and checks that it's prefixed with the theme's code
 *
 * @param string $string
 *
 * @return bool
 */
function gft_id_is_prefixed( $string ) {

	return strpos( $string, STD_PREFIX ) === 0;

}


/**
 * Ensures that a string us prefixed with the theme's code
 *
 * @param string $string
 *
 * @return string
 */
function gft_prefix_id( $string ) {

	return gft_id_is_prefixed( $string ) ? $string : STD_PREFIX . $string;

}


/**
 * Get a theme specific general option
 *
 * @param string $option
 * @param string $default
 *
 * @return mixed|null|void
 *
 * @uses get_option
 */
function gft_get_option( $option, $default = '' ) {

	if ( ! gft_id_is_prefixed( $option ) ) {
		$option = gft_prefix_id( $option );
	}

	return get_option( $option, $default );

}


/**
 * If the URL given is relative this makes that URL absolute
 *
 * @param $url
 *
 * @return string|void
 */
function gft_complete_url( $url ) {

	$destination = $url;
	if ( strpos( $url, 'http' ) !== 0 && $url !== '#' ) {
		$destination = home_url( $url );
	}

	return $destination;

}


/**
 * Get a post's object by its pagename
 *
 * @param        $slug
 * @param string $post_type
 *
 * @return WP_Post|null
 */
function gft_get_post_by_slug( $slug, $post_type = NULL ) {

	if ( $post_type === NULL ) {
		$posts = get_posts( array(
			'post_type'   => get_post_types(),
			'numberposts' => 1,
			'pagename'    => $slug
		) );

	} else {
		$posts = get_posts( array(
			'post_type'   => $post_type,
			'numberposts' => 1,
			'pagename'    => $slug
		) );
	}

	return ! empty( $posts ) ? $posts[0] : NULL;

}


/**
 * Get a page's post object by its pagename
 *
 * @param string $slug
 * @param string $post_type
 *
 * @return WP_Post|null
 *
 * @see gft_get_post_by_slug
 */
function gft_get_page_by_slug( $slug, $post_type = NULL ) {

	return gft_get_post_by_slug( $slug, $post_type );

}


/**
 * Get the permalink of a page by its pagename
 *
 * @param string $slug
 * @param string $post_type
 *
 * @return string
 */
function gft_get_permalink_by_slug( $slug, $post_type = NULL ) {

	$post = gft_get_page_by_slug( $slug, $post_type );
	return ! empty( $post ) ? get_permalink( $post->ID ) : '';

}


/**
 * Get the URL for a post's featured image
 *
 * @param int    $post_id
 * @param string $size
 *
 * @return string
 */
function gft_get_thumbnail_url( $post_id, $size = 'full' ) {

	if ( ! has_post_thumbnail( $post_id ) ) {
		return '';
	}

	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}

	$thumb_data = wp_get_attachment_image_src(
		get_post_thumbnail_id( $post_id ),
		$size,
		true
	);

	return $thumb_data[0];

}


/**
 * Allows one to return the value of a function that can only print its output
 *
 * @param string $output_func
 * @param array  $params
 *
 * @return string
 */
function gft_return_func_output( $output_func, array $params = array() ) {

	ob_start();

	// Call the function that produces the output
	if ( empty( $params ) ) {
		call_user_func( $output_func );
	} else {
		call_user_func_array( $output_func, $params );
	}

	// Get the output
	$output = ob_get_contents();

	// End buffer
	ob_end_clean();

	// Send it
	return $output;

}


/**
 * Takes an associative array of attributes and builds an attribute string
 *
 * @param array $atts
 *
 * @return string
 */
function gft_build_attr_str( array $atts ) {

	$output = '';

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $attr => $val ) {
			$output .= ' ' . $attr . '="' . $val . '"';
		}
	}

	return $output;

}


/**
 * No targeting available. Will strip the first match it finds
 *
 * @param string $attr Attribute name only, e.g. not 'id="some-id"' just 'id'
 * @param string $str  The string to perform the strip on
 *
 * @return mixed
 *
 * todo Strip spaces that sometimes follow attributes
 */
function gft_strip_attr( $attr, $str ) {

	$attr_orig = $attr;
	$attr      = $attr . '="';

	$start = strpos( $str, $attr );

	if ( $start === FALSE ) {
		return $str;
	}

	$length = strpos( $str, '"', $start + strlen( $attr ) ) - ($start - 1);

	if ( $start === 0 ) {
		$length++;
	}

	return str_replace( substr($str, $start, $length), '', $str );

}


/**
 * Set current request as a 404. Be sure run exit() at some point after this to prevent
 * further code from being executed.
 *
 * @param string $template_404
 *
 * todo Reset $post
 */
function gft_do_404( $template_404 = '404.php' ) {

	/** @var WP_Query $wp_query */
	global $wp_query;

	// Set current query as 404
	$wp_query->set_404();

	// Send 404 headers
	status_header( 404, 'Not Found' );

	// Load the 404 template
	locate_template( $template_404, TRUE );

}


/**
 * Get a simple array of all post categories, with each item giving only the name and URL of the
 * category
 *
 * @return array
 */
function gft_get_categories_simple() {

	$categories = get_categories();

	if ( empty( $categories ) ) {
		return array();
	}

	$category_data = array();
	foreach ( $categories as $c ) {
		/** @var WP_Term $c */
		$category_data[] = array(
			'name' => $c->name,
			'url'  => get_category_link( $c->cat_ID )
		);
	}

	return $category_data;

}


/**
 * Gets the given index of an array. Convenience function for avoiding "index not found" notices/errors
 *
 * @param string|int $index
 * @param array      $array
 * @param mixed      $default_value
 *
 * @return mixed
 */
function array_index( $index, array $array, $default_value = '' ) {

	return isset( $array[ $index ] ) ? $array[ $index ] : $default_value;

}


/**
 * Checks single level arrays for emptiness. Covers cases where keys exist but all values qualify as empty
 *
 * @param array $array
 *
 * @return bool
 */
function empty_array_values( array $array ) {

	// Covers completely empty arrays
	if ( empty( $array ) ) {
		return TRUE;
	}

	// For arrays with keys
	$all_empty = TRUE;
	foreach ( $array as $key => $value ) {
		if ( ! empty( $value ) ) {
			$all_empty = FALSE;
			break;
		}
	}

	// Feedback
	return $all_empty;

}


/**
 * Print a closing div
 *
 * Useful for printing a closing <div> on a hook action easily
 */
function __gft_echo_closing_div() {

	echo '</div>';

}




function gft_get_social() {

	// Get company social data
	$socials     = gft_get_social_options_data();
	$social_urls = array();

	foreach ( $socials as $s => $v ) {
		$social_handle = gft_get_option( 'social_' . $s, '' );

		if ( $social_handle !== '' ) {
			$social_data = clt_get_social_options_data( $s );
			$social_urls[ $s ] = sprintf(
				$social_data['format'],
				$social_handle
			);
		}
	}

    return $social_urls;
}


/**
 * Get the sign-up link
 *
 * @param bool $form Determines whether to to show pre-message or signup form
 */
function clt_get_join_url( $form = true ) {

    if ($form) {
        return gft_get_permalink_by_slug('join');
    } else {
        return gft_get_permalink_by_slug('complete');
    }

}
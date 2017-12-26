<?php
/**
 * Function for handling manipulating data used by this theme, post meta mostly but also
 * any data specific to this theme
 */


/**
 * Wrapper for add_post_meta(), used to ensure consistent prefixing of keys
 *
 * @param      $post_id
 * @param      $meta_key
 * @param      $meta_value
 * @param bool $unique
 *
 * @return false|int
 *
 * @see add_post_meta
 */
function gft_add_post_meta( $post_id, $meta_key, $meta_value, $unique = FALSE ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return add_post_meta( $post_id, $meta_key, $meta_value, $unique );

}


/**
 * Wrapper for delete_post_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $post_id
 * @param        $meta_key
 * @param string $meta_value
 *
 * @return bool
 *
 * @see delete_post_meta
 */
function gft_delete_post_meta( $post_id, $meta_key, $meta_value = '' ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return delete_post_meta( $post_id, $meta_key, $meta_value );

}


/**
 * Wrapper for get_post_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $post_id
 * @param string $key
 * @param bool   $single
 *
 * @return mixed
 *
 * @see get_post_meta
 */
function gft_get_post_meta( $post_id, $key = '', $single = FALSE ) {

	if ( ! gft_id_is_prefixed( $key ) ) {
		$key = STD_PREFIX . $key;
	}

	return get_post_meta( $post_id, $key, $single );

}


/**
 * Wrapper for update_post_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $post_id
 * @param        $meta_key
 * @param        $meta_value
 * @param string $prev_value
 *
 * @return bool|int
 *
 * @see update_post_meta
 */
function gft_update_post_meta( $post_id, $meta_key, $meta_value, $prev_value = '' ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );

}


/**
 * Wrapper for add_term_meta(), used to ensure consistent prefixing of keys
 *
 * @param      $term_id
 * @param      $meta_key
 * @param      $meta_value
 * @param bool $unique
 *
 * @return false|int
 *
 * @see add_term_meta
 */
function gft_add_term_meta( $term_id, $meta_key, $meta_value, $unique = FALSE ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return add_term_meta( $term_id, $meta_key, $meta_value, $unique );

}


/**
 * Wrapper for delete_term_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $term_id
 * @param        $meta_key
 * @param string $meta_value
 *
 * @return bool
 *
 * @see delete_term_meta
 */
function gft_delete_term_meta( $term_id, $meta_key, $meta_value = '' ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return delete_term_meta( $term_id, $meta_key, $meta_value );

}


/**
 * Wrapper for get_term_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $term_id
 * @param string $key
 * @param bool   $single
 *
 * @return mixed
 *
 * @see get_term_meta
 */
function gft_get_term_meta( $term_id, $key = '', $single = FALSE ) {

	if ( ! gft_id_is_prefixed( $key ) ) {
		$key = STD_PREFIX . $key;
	}

	return get_term_meta( $term_id, $key, $single );

}


/**
 * Wrapper for update_term_meta(), used to ensure consistent prefixing of keys
 *
 * @param        $term_id
 * @param        $meta_key
 * @param        $meta_value
 * @param string $prev_value
 *
 * @return bool|int
 *
 * @see update_term_meta
 */
function gft_update_term_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {

	if ( ! gft_id_is_prefixed( $meta_key ) ) {
		$meta_key = STD_PREFIX . $meta_key;
	}

	return update_term_meta( $term_id, $meta_key, $meta_value, $prev_value );

}

/**
 *
 * @param $mode
 * @param $name
 * @param $type
 * @param $id
 * @return mixed
 */
function gft_get_cf_data( $mode, $name, $type = null, $id = null ) {

   $return = null;
        $name = gft_prefix_id($name);

       if ( $mode == 'post' && function_exists( 'carbon_get_post_meta' ) ) {
            $return = carbon_get_post_meta( $id, $name, $type );
        }

       if ( $mode == 'the_post' && function_exists( 'carbon_get_the_post_meta' ) ) {
            $return = carbon_get_the_post_meta( $name, $type );
        }

       if ( $mode == 'theme' && function_exists( 'carbon_get_theme_option' ) ) {
            $return = carbon_get_theme_option( $name, $type );
        }

       if ( $mode == 'term' && function_exists( 'carbon_get_term_meta' ) ) {
            $return = carbon_get_term_meta( $id, $name, $type );
        }

       if ( $mode == 'user' && function_exists( 'carbon_get_user_meta' ) ) {
            $return = carbon_get_user_meta( $id, $name, $type );
        }

       if ( $mode == 'comment' && function_exists( 'carbon_get_comment_meta' ) ) {
            $return = carbon_get_comment_meta( $id, $name, $type );
        }


   return $return;
}

/**
 * @return array
 */
function gft_get_social_options_data( $id = NULL ) {

	$socials = array(
		'facebook' => array(
			'id'     => 'facebook',
			'name'   => 'Facebook',
			'label'  => 'Facebook Page',
			'lead'   => 'facebook.com/',
			'format' => 'https://www.facebook.com/%s'
		),
		'twitter' => array(
			'id'     => 'twitter',
			'name'   => 'Twitter',
			'label'  => 'Twitter Handle',
			'lead'   => 'twitter.com/',
			'format' => 'https://twitter.com/%s'
		),
	);

	if ( $id === NULL ) {
		return $socials;
	} else {
		return isset( $socials[ $id ] ) ? $socials[ $id ] : array();
	}

}
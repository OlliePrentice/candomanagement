<?php
/**
 * Functions to help with carrying out tasks in the WordPress CMS
 */


/**
 * Remove the WYSIWYG editor from a CMS editing page by slug
 *
 * @param string $post_name
 */
function gft_admin_hide_editor_by_slug( $post_name ) {

	// Must be in the CMS
	if ( ! is_admin() ) {
		return;
	}

	// Check if we're on the given page by slug name
	if ( gft_admin_edit_get_post_slug() === $post_name ) {
		remove_post_type_support( 'page', 'editor' );
	}

}


/**
 * Get the post object for the current edit page
 *
 * @return array|null|WP_Post
 */
function gft_admin_edit_get_post() {

	if ( ! is_admin() ) {
		return NULL;
	}

	$current_post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

	if ( empty( $current_post_id ) ) {
		$current_post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );
	}

	return ! empty( $current_post_id ) ? get_post( $current_post_id ) : NULL;

}


/**
 * Get a property of the post object of the current edit page
 *
 * @param $prop
 *
 * @return mixed|null
 */
function gft_admin_edit_get_post_prop( $prop ) {

	// Get post object
	$post = gft_admin_edit_get_post();

	// Return the property if $post is WP_Post instance
	return $post instanceof WP_Post ? $post->{$prop} : NULL;

}


/**
 * Get the slug of the current edit page's post
 *
 * @return string|null
 *
 * @see gft_admin_edit_get_post_prop
 */
function gft_admin_edit_get_post_slug() {

	return gft_admin_edit_get_post_prop( 'post_name' );

}


/**
 * Attempt to find out what the current post type is. Typically works best on the "edit" version of a page
 *
 * @return false|string
 */
function gft_admin_edit_get_post_type() {

	// Ensure we've got an actual post object
	$post = gft_admin_edit_get_post();

	// Attempt to find a post type
	if ( ! empty( $post ) ) {
		$post_type = get_post_type( $post );
	} else {
		$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : FALSE;
	}

	// Send it
	return $post_type;

}

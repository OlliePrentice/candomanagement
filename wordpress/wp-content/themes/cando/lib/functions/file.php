<?php
/**
 * Functions for finding and getting files within the theme's directory
 */


/**
 * Get the URI to the assets directory
 *
 * @return string
 */
function gft_get_asset_dir_uri() {

	return constant( strtoupper( STD_PREFIX ) . 'ASSETS_URL' );

}


/**
 * Get the URI to an asset file
 *
 * @param string $slug
 * @param string $type
 *
 * @return string
 */
function gft_get_asset( $slug, $type = '' ) {

	// Ascertain common asset directories
	switch( $type ) {
		case 'css':
			$dir = 'css';
			break;

		case 'font':
			$dir = 'fonts';
			break;

		case 'gif':
			$dir = 'gif';
			break;

		case 'img':
			$dir = 'images';
			break;

		case 'svg':
			$dir = 'images';
			break;

		case 'js':
			$dir = 'js';
			break;

		case 'vid':
			$dir = 'video';
			break;

		default:
			$dir = '';
			break;
	}

	// Build the asset URL
	$asset_url  = gft_get_asset_dir_uri();
	if ( $dir !== '' ) {
		$asset_url .= '/' . $dir;
	}
	$asset_url .= $slug;

	// Send it
	return $asset_url;

}


/**
 * Generates a URI for a placeholder image sourced from placehold.it
 *
 * @param int $width
 * @param int $height
 *
 * @return string
 *
 * @link http://placehold.it
 */
function gft_get_ph_img_uri( $width, $height = NULL ) {

	$uri  = 'http://placehold.it/' . $width;
	if ( ! empty( $height ) ) {
		$uri .= 'x' . $height;
	}

	return $uri;

}


/**
 * Get a child path of the theme directory
 *
 * @param string $directory_name Either a path relative to the theme dir, or a directory
 *                               constant name, e.g. to get the value of GFT_CLASS_DIR
 *                               set $directory_name = 'class'
 * @param string $more           Affixed without tampering to the final rendered $path
 *
 * @return string
 */
function gft_get_theme_directory( $directory_name = '', $more = '' ) {

	// We only deal with this theme's paths
	$path = CHILD_DIR;

	// See if the directory name is actually a directory constant identifier
	$constant = strtoupper( STD_PREFIX . $directory_name ) . '_DIR';
	if ( defined( $constant ) ) {
		$path = constant( $constant );
	}

	// See if $directory_name is explicit path (relative to the theme)
	if ( file_exists( $path . $directory_name ) ) {
		$path .= $directory_name;
	}

	// Affix an additional path arbitrarily. Useful when $directory_name identifies a constant
	if ( ! empty( $more ) ) {
		$path .= $more;
	}

	// Send it
	return $path;

}


/**
 * Use where we don't want to require a file within init but in specific cases instead
 *
 * @param string $directory_name
 * @param string $file_name
 *
 * @uses gft_get_theme_directory
 */
function gft_require( $directory_name, $file_name ) {

	require_once( gft_get_theme_directory( $directory_name, $file_name ) );

}


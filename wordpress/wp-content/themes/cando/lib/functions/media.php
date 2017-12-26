<?php
/**
 * For those functions largely related to handling and
 * manipulating media: images and videos and such.
 */


/**
 * Get the embed code for a video by its ID from the given vendor
 *
 * @param string $vendor      The provider for the video, e.g. youtube
 * @param string $id          The video ID on the vendor's platform
 * @param array  $attr        An array of attributes to be applied to the video element
 * @param string $vid_context The context of the video for attribute filtering purposes
 *
 * @return mixed
 */
function gft_get_video_embed( $vendor, $id, Array $attr = array(), $vid_context = '' ) {

	$vid_id  = 'vid-' . $id;

	// For context: First check if an ID for the video element has been set
	if ( ! empty( $attr['id'] ) ) {
		$context = $attr['id'];
	}

	// For context: Context has been explicitly provided, this overrides any IDs or anything else
	if ( ! empty( $vid_context ) ) {
		$context = 'vid-' . $vid_context;
	}

	// For context: Nothing gleaned context providing settings, use the video ID
	if ( empty( $context ) ) {
		$context = $vid_id;
	}

	// Parse args
	$attr = wp_parse_args( $attr, array(
		'id'              => $vid_id,
		'class'           => 'vid',
		'allowfullscreen' => true
	) );

	// Get the embed code
	return call_user_func_array( STD_PREFIX . 'get_video_embed_' . $vendor, array( $id, $attr, $context ) );

}


/**
 * Generate the embed code for a YouTube video by its ID
 *
 * Should not be used directly. Use gft_get_video_embed() instead
 *
 * @param        $id
 * @param array  $attr
 * @param string $vid_context
 *
 * @return string
 *
 * @see gft_get_video_embed
 */
function gft_get_video_embed_youtube( $id, Array $attr = array(), $vid_context = '' ) {

	$output  = '<iframe src="https://www.youtube.com/embed/' . $id . '?feature=oembed" frameborder="0" ';
	$output .= genesis_attr( $vid_context, $attr );
	$output .= '></iframe>';

	return $output;

}


/**
 * Generate the embed code for a Vimeo video by its ID
 *
 * Should not be used directly. Use gft_get_video_embed() instead
 *
 * @param        $id
 * @param array  $attr
 * @param string $vid_context
 *
 * @return string
 *
 * @see gft_get_video_embed
 */
function gft_get_video_embed_vimeo( $id, Array $attr = array(), $vid_context = '' ) {

	$output  = '<iframe src="https://player.vimeo.com/video/' . $id . '" frameborder="0"';
	$output .= genesis_attr( $vid_context, $attr );
	$output .= '></iframe>';

	return $output;

}

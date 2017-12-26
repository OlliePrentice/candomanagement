<?php
/**
 * Template tags, largely for building elements and blocks specific to this theme
 */


/**
 * Convenience function for returning the value of the \GFT\SVG::output method, which echoes its output
 *
 * @param string $id
 * @param array  $attr
 *
 * @return string
 *
 * @uses \GFT\SVG::output
 */
function gft_get_svg_output( $id, array $attr = array() ) {

	global $GFT_SVG;

	if ( ! empty( $GFT_SVG ) ) {
		return gft_return_func_output( array( $GFT_SVG, 'output' ), array( $id, $attr ) );
	} else {
		return '';
	}

}


/**
 * Outputs html for stacked SVGs
 *
 * @param string $file      The filename
 * @param string $name      The layer ID for stacked SVGs
 * @param int    $width     Width attribute of the image
 * @param int    $height    Height attribute of the image
 * @param string $class     List of CSS classes
 *
 * @return string
 */
function gft_get_svg_inline($file, $name = '', $attr = array() ) {

        $opts = array_merge( array(
            'width' => 0,
            'height'=> 0,
            'class' => ''
        ), $attr);

        $class = $opts['class'];

        // Set file path
		$path = (!empty($name))? $path = '/svg/'.$file : '/'.$file ;

        // Get full file path
		$fullpath = gft_get_asset($path, 'img');
		$fullpath = str_replace('http://','',$fullpath);
		$fullpath = str_replace('https://','',$fullpath);
		if (substr($fullpath,0,2) == '//') {
			substr($fullpath, 2, strlen($fullpath));
		}
		$pos = strpos($fullpath, '/');
		$fullpath = str_replace(substr($fullpath, 0, $pos), '', $fullpath);

        // If empty, set default CSS classes
		if ( empty($class) )
			$class = 'icon '.$file.' '.$name;

        // Create HTML for displaying the SVG
        $html = '<svg class="'.$class.'"';
            if ($opts['width'] > 0)  { $html .= ' width="'.$opts['width'].'"'; }
            if ($opts['height'] > 0) { $html .= ' height="'.$opts['height'].'"'; }
        $html .= '><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="'.$fullpath.'.svg';
            if ($name != '') { $html .= '#'.$name; }
        $html .= '"></use></svg>';


	return $html;
}


/**
 * Build the hamburger menu icon HTML
 *
 * @param string $class
 *
 * @return string
 */
function gft_hamburger( $class = '' ) {
	$output  = ( empty( $class ) ) ? '<button class="hamburger">' : '<button class="' . $class . ' hamburger">';
	$output .= '<span></span>';
	$output .= '<span></span>';
	$output .= '<span></span>';
	$output .= '</button>';
	return $output;
}



/**
 * Build the HTML for the primary button style
 *
 * @param string $text
 * @param string $url
 * @param array  $options
 *
 * @return string
 */
function clt_get_btn_primary( $text, $url = '#', Array $options = array() ) {

	$options = wp_parse_args( $options, array(
		'tag'  => 'a',
		'attr' => array(),
		'dir'  => 'right'
	) );

	// Ensure the correct button class is in there
	if ( ! isset( $options['attr']['class'] ) ) {
		$options['attr']['class'] = 'btn btn-primary';
	} else {
		$options['attr']['class'] .= ' btn btn-primary';
	}

	// For the arrow direction
	$options['attr']['class'] .= ' ' . $options['dir'];

	// Use the `$url` param
	unset( $options['attr']['href'] );

	// A format with the href attribute, one without
	if ( $url === FALSE || empty( $url ) ) {
		$format = '<%1$s %2$s><span class="txt">%3$s</span></%1$s>';
	} else {
		$format = '<%1$s href="%4$s" %2$s><span class="txt">%3$s</span></%1$s>';
	}

	// Send it
	return sprintf(
		$format,
		$options['tag'],
		gft_build_attr_str( $options['attr'] ),
		$text,
		$url
	);

}


/**
 * Passes args directly to mbs_get_btn_primary()
 *
 * @param string $text
 * @param string $url
 * @param array  $options
 *
 * @uses mbs_get_btn_primary
 */
function clt_btn_primary( $text, $url = '', Array $options = array() ) {

	echo clt_get_btn_primary(  $text, $url, $options );

}


/**
 * Build the HTML for the secondary button style
 *
 * @param string $text
 * @param string $url
 * @param array  $options
 *
 * @return string
 */
function clt_get_btn_secondary( $text, $url = '#', Array $options = array() ) {

	$options = wp_parse_args( $options, array(
		'tag'  => 'a',
		'attr' => array()
	) );

	// Ensure the correct button class is in there
	if ( ! isset( $options['attr']['class'] ) ) {
		$options['attr']['class'] = 'btn btn-secondary';
	} else {
		$options['attr']['class'] .= ' btn btn-secondary';
	}

	// Use the `$url` param
	unset( $options['attr']['href'] );

	// A format with the href attribute, one without
	if ( $url === FALSE || empty( $url ) ) {
		$format = '<%1$s %2$s>%3$s</%1$s>';
	} else {
		$format = '<%1$s href="%4$s" %2$s>%3$s</%1$s>';
	}

	// Send it
	return sprintf(
		$format,
		$options['tag'],
		gft_build_attr_str( $options['attr'] ),
		$text,
		$url
	);

}


/**
 * Passes args directly to mbs_get_btn_secondary()
 *
 * @param string $text
 * @param string $url
 * @param array  $options
 *
 * @uses mbs_get_btn_secondary
 */
function clt_btn_secondary( $text, $url = '', Array $options = array() ) {

	echo clt_get_btn_secondary(  $text, $url, $options );

}
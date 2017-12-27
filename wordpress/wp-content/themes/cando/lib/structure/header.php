<?php

remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
//* Remove Skip Links from a template
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

add_filter('genesis_seo_title', 'prentice_do_site_logo');
//remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
/**
 * Add the logo image to the logo text at the head of the site
 *
 * @param $title
 *
 * @return mixed
 */

function prentice_do_site_logo( $title ) {

    //$logo_src =  '<span class="site-logo">'.gft_get_svg_inline('main', 'logo', array('width'=>210, 'height'=>20, 'class'=>'logo') ).'</span>';
    $logo_src =  '<img src="'.gft_get_asset('/svg/cd-logo.svg', 'img').'" alt="Can Do Managment Consultants Logo">';

    $site_name = get_bloginfo( 'name' );

    if ( strpos( $title, $site_name ) !== FALSE ) {

        //$title_html  = '<span class="screen-reader-text">' . $site_name . '</span>';
        $title_html = $logo_src;

        $title = str_replace(
            '>' . $site_name . '<',
            '>' . $title_html . '<',
            $title
        );
    }

    return $title;

}



add_filter( 'genesis_attr_site-header', 'prentice_add_class_to_header' );
function prentice_add_class_to_header( $attributes ) {

    $attributes['class'] .= ' container';
    return $attributes;
}
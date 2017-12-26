<?php

remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
//* Remove Skip Links from a template
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );

//add_filter('genesis_seo_title', 'prent_do_site_logo');
remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
/**
 * Add the logo image to the logo text at the head of the site
 *
 * @param $title
 *
 * @return mixed
 */

function prent_do_site_logo( $title ) {

    //$logo_src =  '<span class="site-logo">'.gft_get_svg_inline('main', 'logo', array('width'=>210, 'height'=>20, 'class'=>'logo') ).'</span>';
    $logo_src =  '<span class="site-logo"><img src="'.gft_get_asset('/svg/nowhealthcaregroup-logo_reverse.svg', 'img').'"></span>';

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

add_filter( 'genesis_nav_items', 'be_follow_icons', 10, 2 );
add_filter( 'wp_nav_menu_items', 'be_follow_icons', 10, 2 );

function be_follow_icons($menu, $args) {
    $args = (array)$args;
    if ( 'primary' !== $args['theme_location']  )
        return $menu;
    $hamburger = '<div class="hamburger-menu"><div class="bar"></div></div><ul class="nav-links-inner">';
    $terms = '<li class="privacy"><a href="' . home_url('/privacy-policy') . '">Privacy Policy</a></li>';
    return $hamburger . $menu . $terms . '</ul>';
}

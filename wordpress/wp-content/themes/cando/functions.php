<?php

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
require_once( get_stylesheet_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Now Patient' );
define( 'CHILD_THEME_URL', WP_SITEURL );
define( 'CHILD_THEME_VERSION', '0.0.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

flush_rewrite_rules( false );




/* INCLUDES
=========== */

/* ACTIONS
========== */

add_action('upload_mimes', 'prentice_add_file_types_to_uploads');
add_action( 'genesis_after_header', 'prentice_single_page_header', 8 );

/* FILTERS
========== */

add_filter( 'genesis_pre_load_favicon', 'prentice_genesis_favicon' );



/* FUNCTIONS
============ */





//add SVG to allowed file uploads
function prentice_add_file_types_to_uploads($file_types){

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );

    return $file_types;
}

/** Adding custom Favicon */
function prentice_genesis_favicon( $favicon_url ) {
    return gft_get_asset('/icon/favicon.ico', 'img');
}




?>
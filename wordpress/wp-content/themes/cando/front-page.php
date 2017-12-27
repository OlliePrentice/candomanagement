
<?php

/**
 * Remove page heading
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_format_image', 4 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );


// Get Text Demo
//add_action('genesis_entry_content', 'prentice_get_text_demo');
//
//function prentice_get_text_demo() {
//
//    gft_get_template_content_part('content', 'text-demo');
//
//
//}



// Get Contact Block
add_action('genesis_entry_content', 'prentice_get_link_block');

function prentice_get_link_block() {

    gft_get_template_component_part('component', 'link-block');


}






/**
 *
 * Run Genesis
 *
 */
genesis();
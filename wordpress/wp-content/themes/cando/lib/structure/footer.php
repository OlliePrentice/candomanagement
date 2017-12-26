<?php

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', '_prent_do_footer' );
/**
 * Remove the Genesis default footer and pull in our own
 */
function _prent_do_footer() {

    // Get company social data
    $social_urls = gft_get_social();

    // Prepare vars for passing through to template parts
    $footer_vars = compact( 'social_urls' );

    // Load footer template
    gft_get_template_structure_part( 'footer', NULL, $footer_vars );

}


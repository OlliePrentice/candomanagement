<?php

/**
 * Register fields to specific page or posts
 *
 * e.g. gft_admin_edit_get_post_type() === 'post'
 * e.g. gft_admin_edit_get_post() === 'about-us'
 *
 * Call function - add_action('gft_setup', 'theme_function_name');
 *
 */


add_action('gft_setup', 'prentice_register_field_to_post_type');

function prentice_register_field_to_post_type() {

    if(gft_admin_edit_get_post_type() === 'page'){
        prentice_register_affiliates_carbon();
    }
}
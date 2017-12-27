<?php


add_action('wp_enqueue_scripts', 'prentice_enqueue_scripts');

/**
 * Register and enqueue scripts
 */

function prentice_enqueue_scripts()
{

    /**
     * CSS
     */
    wp_register_style('fonts', 'https://fonts.googleapis.com/css?family=Frank+Ruhl+Libre', NULL, '1.0', 'screen');
    wp_register_style('lib', gft_get_asset('/min/lib.min.css', 'css'), NULL, '1.0', 'screen');
    wp_register_style('styles', gft_get_asset('/min/styles.min.css', 'css'), array('lib'), '1.0', 'screen');

    wp_enqueue_style('fonts');
    wp_enqueue_style('lib');
    wp_enqueue_style('styles');


    /**
     * JS
     */

    /* jQuery */
    wp_register_script('vendors', gft_get_asset('/min/vendors.min.js', 'js'), NULL, '1.0', true);
    wp_register_script('custom', gft_get_asset('/min/custom.min.js', 'js'), array('vendors'), '1.0', true);

    wp_enqueue_script('vendors');
    wp_enqueue_script('custom');

}
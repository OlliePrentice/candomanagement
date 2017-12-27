<?php

/**
 * Register Quick Links Tab Carbon Fields
 */


function prentice_register_link_block_carbon()
{

    global $GFT_CF;
    if ($GFT_CF->getContainer('page') === false) {
        $container = $GFT_CF->setContainer('page', 'post_meta', __('Page Content Blocks', 'prentice'));
        if (!$container) {
            return;
        }
        $container->show_on_post_type('page');
    }
    $GFT_CF->setTab('page', __('Page Link Block', 'prentice'), array(
        $GFT_CF->field('image', gft_prefix_id('link_block_image'), __('Page Link Image', 'prentice')),
        $GFT_CF->field('text', gft_prefix_id('link_block_title'), __('Page Link Title', 'prentice')),
    ));

}
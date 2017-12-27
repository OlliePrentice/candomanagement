<?php

/**
 * Register Page Content Tab Carbon Fields
 */


function prentice_register_header_tab_carbon()
{

    global $GFT_CF;
    if ($GFT_CF->getContainer('page') === false) {
        $container = $GFT_CF->setContainer('page', 'post_meta', __('Page Content Blocks', 'prentice'));
        if (!$container) {
            return;
        }
        $container->show_on_post_type('page');
    }
    $GFT_CF->setTab('page', __('Page Header', 'prentice'), array(
        $GFT_CF->field('text', gft_prefix_id('header_block_title'), __('Header Block Title', 'prentice')),
        $GFT_CF->field('text', gft_prefix_id('header_block_subtitle'), __('Header Block Subtitle', 'prentice')),
        $GFT_CF->field('image', gft_prefix_id('header_block_image'), __('Header Block Background Image', 'prentice')),
        $GFT_CF->field('text', gft_prefix_id('header_block_button'), __('Header Block Button Text', 'prentice')),
        $GFT_CF->field('select', gft_prefix_id('header_block_button_link'), __('Header Block Button Link', 'prentice'))->add_options(array(
            'home' => 'Home',
            'about-us' => 'About Us',
            'our-services' => 'Our Services',
            'blog' => 'Blog',
            'contact' => 'Contact Us',
        )),
        $GFT_CF->field('checkbox', gft_prefix_id('content_block_hide_button'), __('Don\'t Show Header Block Button', 'prentice'))->set_option_value( 'hidden' ),

    ));

}
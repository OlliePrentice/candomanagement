<?php

/**
 * Register Page Content Tab Carbon Fields
 */


function prentice_register_content_tab_carbon()
{

    global $GFT_CF;
    if ($GFT_CF->getContainer('page') === false) {
        $container = $GFT_CF->setContainer('page', 'post_meta', __('Page Content Blocks', 'prentice'));
        if (!$container) {
            return;
        }
        $container->show_on_post_type('page');
    }
    $GFT_CF->setTab('page', __('Content Blocks', 'prentice'), array(
        $GFT_CF->field('complex', gft_prefix_id('content_blocks'), 'Content')->set_layout('tabbed-horizontal')->add_fields( array(
            $GFT_CF->field('text', gft_prefix_id('content_block_heading'), __('Content Block Heading', 'prentice')),
            $GFT_CF->field('rich_text', gft_prefix_id('content_block_text'), __('Content Block Text', 'prentice')),
            $GFT_CF->field('image', gft_prefix_id('content_block_image'), __('Content Block Image', 'prentice')),
            $GFT_CF->field('text', gft_prefix_id('content_block_button'), __('Content Block Button Text', 'prentice')),
            $GFT_CF->field('select', gft_prefix_id('content_block_button_link'), __('Content Block Button Link', 'prentice'))->add_options( array(
                'home' => 'Home',
                'about-us' => 'About Us',
                'our-services' => 'Our Services',
                'blog' => 'Blog',
                'contact' => 'Contact Us',
            )),
            $GFT_CF->field('checkbox', gft_prefix_id('content_block_hide_button'), __('Don\'t Show Content Block Button', 'prentice'))->set_option_value( 'hidden' ),
            $GFT_CF->field('checkbox', gft_prefix_id('content_block_alternate_colours'), __('Content Block Alternate Colours', 'prentice'))->set_option_value( 'alt-colours' ),
        ))
    ));

}
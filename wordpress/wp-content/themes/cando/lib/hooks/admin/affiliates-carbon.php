<?php

/**
 * Register Affiliate Logos Carbon Fields
 */


function prentice_register_affiliates_carbon()
{

    global $GFT_CF;
    if ($GFT_CF->getContainer('affiliates-carbon') === false) {
        $container = $GFT_CF->setContainer('affiliates-carbon', 'post_meta', __('Affiliate Logos', 'prentice'));
        if (!$container) {
            return;
        }
        $container->show_on_post_type('page');
    }
    $GFT_CF->setTab('affiliates-carbon', __('Tab', 'prentice'), array(
        $GFT_CF->field('complex', gft_prefix_id('affiliate_logos'), 'Logos')->set_layout('tabbed-horizontal')->add_fields( array(
        $GFT_CF->field('image', gft_prefix_id('affiliate_image'), __('Logo', 'prentice')),
        ) )
    ));

}
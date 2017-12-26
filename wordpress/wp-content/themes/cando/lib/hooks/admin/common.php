<?php

/**
 *
 * Use custom wrapper for carbon fields to register new meta boxes in wordpress admin
 *
 * Set a container and fields
 *
 * e.g. global $GFT_CF;
 * if ( $GFT_CF->getContainer('page') === false ) {
 *     $container = $GFT_CF->setContainer('page', 'post_meta', __( 'Page Options', 'themeprefix' ) );
 *     if ( !$container ) { return; }
 *     $container->show_on_post_type( 'page' );
 * }
 * $GFT_CF->setTab( 'page', __( 'Page Hero', 'themeprefix' ), array(
 *     $GFT_CF->field( 'text', gft_prefix_id('hero_prefix'), __( 'Pre Title', 'themeprefix' ) ),
 * ));
 *
 *
 */

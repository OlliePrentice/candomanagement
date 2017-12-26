<?php

/**
 * Page for registering all theme post types
 */

// faq

function prentice_custom_post_faq() {
    $labels = array(
        'name'               => _x( 'FAQ', 'post type general name' ),
        'singular_name'      => _x( 'FAQ', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'nowpatient' ),
        'add_new_item'       => __( 'Add New FAQ' ),
        'edit_item'          => __( 'Edit FAQ' ),
        'new_item'           => __( 'New FAQ' ),
        'all_items'          => __( 'All FAQs' ),
        'view_item'          => __( 'View FAQ' ),
        'search_items'       => __( 'Search FAQ' ),
        'not_found'          => __( 'No FAQs found' ),
        'not_found_in_trash' => __( 'No FAQs found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'FAQ'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Hold all of our FAQs',
        'public'        => false,
        'publicly_queryable' => true,
        'show_ui'       => true,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'supports'      => array('title','editor'),
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-editor-help'
    );
    register_post_type( 'faq', $args );
}
add_action( 'init', 'prentice_custom_post_faq' );

function prentice_faq_updated_messages( $messages ) {
    global $post, $post_ID;
    $messages['faq'] = array(
        0 => '',
        1 => sprintf( __('FAQ updated.') ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('FAQ updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('FAQ restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('FAQ published.') ),
        7 => __('FAQ saved.'),
        8 => sprintf( __('FAQ submitted.') ),
        9 => sprintf( __('FAQ scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
        10 => sprintf( __('FAQ draft updated.') ),
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'prentice_faq_updated_messages' );


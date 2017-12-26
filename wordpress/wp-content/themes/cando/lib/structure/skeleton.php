<?php

add_filter( 'body_class', '_prent_do_page_body_class' );
/**
 * Add page specific class to the <body> element using the page slug
 *
 * NOTE: Be aware that there is sometimes crossover, i.e. `is_product_category` will also catch `is_archive`
 *
 * @param $classes
 *
 * @return array
 */
function _prent_do_page_body_class( $classes ) {

    global $post;

    if ( ! empty( $post ) ) {
        // Page types that need slight amendment to class name output, or don't require one at all
        $type_functions = array(
            'is_404',
            // Save default template for last
            'is_default_template'
        );

        // As standard, the post name is the class name
        $post_name_as_class = TRUE;

        // Loop through the functions that check page type
        foreach ( $type_functions as $func ) {
            // Do we have a match?
            if ( function_exists( $func ) && call_user_func( $func ) ) {
                // Prevents default from being used
                $post_name_as_class = FALSE;

                // Do amendment as appropriate based on function name
                switch ( $func ) {
                    case 'is_404':
                        $classes[] = 'page__404';
                        break 2;

                    case 'is_default_template':
                        $post_name_as_class = TRUE;
                        $classes[] = 'page__template-default';
                        break 2;
                }
            }
        }

        // Default class name
        if ( $post_name_as_class ) {
            $classes[] = 'page__' . $post->post_name;
        }
    }

    return $classes;

}


add_filter('show_admin_bar', '_prent_do_remove_adminbar');
/**
 * Disable Admin Bar on frontend
 *
 * @param bool $status
 * @return bool
 */
function _prent_do_remove_adminbar( $status ) {
    return false;
}



/*
 * Remove and un-register Genesis sidebars
 */
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );


//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );
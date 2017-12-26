<?php

// Useful definitions
define( 'STD_PREFIX', 'prentice_' );

// Administrative information
define( 'PRENTICE_ADMIN_EMAIL', 'info@prenticemanagement.com' );


// Filesystem and URL paths
define( 'PRENTICE_LIB_DIR', CHILD_DIR . '/lib' );
define( 'PRENTICE_ASSETS_DIR', CHILD_DIR . '/assets' );
define( 'PRENTICE_CLASS_DIR', PRENTICE_LIB_DIR . '/class' );
define( 'PRENTICE_HOOKS_DIR', PRENTICE_LIB_DIR . '/hooks' );
define( 'PRENTICE_FUNCTIONS_DIR', PRENTICE_LIB_DIR . '/functions' );
define( 'PRENTICE_STRUCTURE_DIR', PRENTICE_LIB_DIR . '/structure' );

define( 'PRENTICE_LIB_URL', CHILD_URL . '/lib' );
define( 'PRENTICE_ASSETS_URL', CHILD_URL . '/assets' );


add_action( 'gft_init', '_prentice_load_classes' );
/**
 * Load classes
 */
function _prentice_load_classes() {
	require_once( PRENTICE_CLASS_DIR . '/Admin/Builder.php' );
	require_once( PRENTICE_CLASS_DIR . '/Admin/CarbonFields.php' );
	require_once( PRENTICE_CLASS_DIR . '/Admin/Notice.php' );
	require_once( PRENTICE_CLASS_DIR . '/Template/Row.php' );
	require_once( PRENTICE_CLASS_DIR . '/Debug.php' );
	require_once( PRENTICE_CLASS_DIR . '/SVG.php' );
}


add_action( 'gft_init', '_prentice_load_functions' );
/**
 * Load functions
 */
function _prentice_load_functions() {
	require_once( PRENTICE_FUNCTIONS_DIR . '/functions.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/file.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/data.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/admin.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/media.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/template.php' );
	require_once( PRENTICE_FUNCTIONS_DIR . '/template-tag.php' );
}


add_action( 'gft_init', '_prentice_load_hooks' );
/**
 * Load hooks
 */
function _prentice_load_hooks() {

    require_once( PRENTICE_HOOKS_DIR . '/admin/affiliates-carbon.php' );
	require_once( PRENTICE_HOOKS_DIR . '/admin/admin-carbon.php' );
	require_once( PRENTICE_HOOKS_DIR . '/admin/post-types.php' );
	require_once( PRENTICE_HOOKS_DIR . '/assets.php' );
	require_once( PRENTICE_HOOKS_DIR . '/navigation.php' );
}


add_action( 'gft_init', '_prentice_load_structure' );
/**
 * Load structure
 */
function _prentice_load_structure() {
	require_once( PRENTICE_STRUCTURE_DIR . '/skeleton.php' );
	require_once( PRENTICE_STRUCTURE_DIR . '/header.php' );
	require_once( PRENTICE_STRUCTURE_DIR . '/footer.php' );
}


// Run the gft_init hook
do_action( 'gft_init' );

// Run the gft_setup hook
do_action( 'gft_setup' );

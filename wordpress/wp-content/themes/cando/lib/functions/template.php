<?php
/**
 * Functions useful for managing templates and template parts
 */


/**
 * Get a template from the theme's template directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @see get_template_part
 */
function gft_get_template_part( $slug, $name = NULL, Array $vars = array() ) {

	foreach ( $vars as $var_name => $var_value ) {
		set_query_var( $var_name, $var_value );
	}

	get_template_part( 'tpl/' . $slug, $name );

	foreach ( $vars as $var_name => $var_value ) {
		set_query_var( $var_name, NULL );
	}

}


/**
 * Return a template from the theme's template directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @return string
 *
 * @see gft_get_template_part
 */
function gft_return_template_part( $slug, $name = NULL, Array $vars = array() ) {

	return gft_return_func_output( 'gft_get_template_part', array( $slug, $name, $vars ) );

}


/**
 * Get a template from the theme's structural templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @see gft_get_template_part
 */
function gft_get_template_structure_part( $slug, $name = NULL, Array $vars = array() ) {

	gft_get_template_part( 'structure/' . $slug, $name, $vars );

}


/**
 * Return a template from the theme's structural templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @return string
 *
 * @see gft_return_template_part
 */
function gft_return_template_structure_part( $slug, $name = NULL, Array $vars = array() ) {

	return gft_return_template_part( 'structure/' . $slug, $name, $vars );

}


/**
 * Get a template from the theme's admin templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @see gft_get_template_part
 */
function gft_get_template_admin_part( $slug, $name = NULL, Array $vars = array() ) {

	gft_get_template_part( 'admin/' . $slug, $name, $vars );

}


/**
 * Return a template from the theme's admin templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @return string
 *
 * @see gft_return_template_part
 */
function gft_return_template_admin_part( $slug, $name = NULL, Array $vars = array() ) {

	return gft_return_template_part( 'admin/' . $slug, $name, $vars );

}


/**
 * Get a template from the theme's component templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @see gft_get_template_part
 */
function gft_get_template_component_part( $slug, $name = NULL, Array $vars = array() ) {

	gft_get_template_part( 'component/' . $slug, $name, $vars );

}


/**
 * Return a template from the theme's component templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @return string
 *
 * @see gft_return_template_part
 */
function gft_return_template_component_part( $slug, $name = NULL, Array $vars = array() ) {

	return gft_return_template_part( 'component/' . $slug, $name, $vars );

}


/**
 * Get a template from the theme's content templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @see gft_get_template_part
 */
function gft_get_template_content_part( $slug, $name = NULL, Array $vars = array() ) {

	gft_get_template_part( 'content/' . $slug, $name, $vars );

}


/**
 * Return a template from the theme's content templates directory
 *
 * @param string $slug
 * @param string $name
 * @param array  $vars
 *
 * @return string
 *
 * @see gft_return_template_part
 */
function gft_return_template_content_part( $slug, $name = NULL, Array $vars = array() ) {

	return gft_return_template_part( 'content/' . $slug, $name, $vars );

}


/**
 * Load a page template
 *
 * @param $name
 *
 * @return string
 */
function gft_load_page_template( $name ) {

	return locate_template( 'page-templates/' . $name . '.php', TRUE );

}


/**
 * Removes the default template classes from a given template
 *
 * To be run on the `body_class` action; works only on pages running under the default template.
 *
 * @param array $classes
 *
 * @return array
 */
function _gft_do_body_class_remove_default( $classes ) {

	foreach ( $classes as $i => $class_name ) {
		if ( $class_name === 'page-template-default' || $class_name === 'page__template-default' ) {
			unset( $classes[ $i ] );
		}
	}

	return $classes;

}


/**
 * To be used on `genesis_attr_*` hooks. Adds a class to the target element that
 * ensures that it's is only seen by screen readers
 *
 * @param array $attributes
 *
 * @return array
 */
function _gft_show_only_to_sr( $attributes ) {

	$attributes['class'] .= ' show-for-sr';

	return $attributes;

}


/**
 * Check if current page is using the default template
 *
 * @return bool
 */
function is_default_template() {

	return is_page_template( 'default' );

}

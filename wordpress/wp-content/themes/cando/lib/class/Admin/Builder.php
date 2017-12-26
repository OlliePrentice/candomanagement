<?php
namespace GFT\Admin;

/**
 * Class Builder
 *
 * @version 1.0.0
 * @package GFT\Admin
 */
class Builder {

	/**
	 * @var   array
	 * @since 1.0.0
	 */
	private $meta_boxes = array();

	/**
	 * @var   bool
	 * @since 1.0.0
	 */
	private $boxes_registered = FALSE;


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Doesn't register at this moment, only at the appropriate hook later on
		$this->register_meta_boxes();

	}


	/**
	 * Register with WP all meta boxes that have been set with this class instance
	 *
	 * @since 1.0.0
	 */
	private function register_meta_boxes() {

		$instance = $this;

		// Can't use `$this` within closure, must send $instance in
		add_action( 'admin_head', function() use( &$instance ) {

			foreach ( $instance->get_meta_boxes() as $meta_box_data ) {
				// Check for callbacks that call for an admin template file
				if ( ! is_array( $meta_box_data['callback'] ) && strpos( $meta_box_data['callback'], 'tpl-' ) === 0 ) {
					// Save callback arguments which will actually be variables to be passed to the template
					$callback_args = array();
					if ( ! empty( $meta_box_data['callback_args'] ) ) {
						$callback_args[] = $meta_box_data['callback_args'];
					}

					/*
					 * `callback_args` will be passed to an `gft_get_template_*` function so
					 * here we rearrange the arguments to match the argument signature of those
					 * functions.
					 */
					array_unshift( $callback_args, str_replace( 'tpl-', '', $meta_box_data['callback'] ) );

					// Now that the callback arguments have been properly arranged we can re-set them on the meta box
					$meta_box_data['callback_args'] = $callback_args;

					// Set the callback to the method of this class that finds the correct template
					$meta_box_data['callback'] = array( $instance, '_get_admin_template' );
				}

				call_user_func_array( 'add_meta_box', $meta_box_data );
			}

			$instance->set_boxes_registered();

		} );

	}


	/**
	 * Format an ID appropriately for this theme
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function id( $id ) {

		return STD_PREFIX . $id;

	}


	/**
	 * Establish that the meta boxes set with this instance have been registered with WP
	 *
	 * While public, usage of this method shouldn't be necessary. It's only public for
	 * backwards compatibility with PHP <= 5.3, where `$this` can not be used within
	 * closures. See register_meta_boxes if you're curious.
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function set_boxes_registered() {

		$this->boxes_registered = TRUE;

	}


	/**
	 * Check if the meta boxes set with this instance have been registered with WP
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function boxes_registered() {

		return $this->boxes_registered;

	}


	/**
	 * Get the index of a meta box by ID
	 *
	 * @param $id
	 *
	 * @return int
	 *
	 * @since 1.0.0
	 */
	public function locate_meta_box_index( $id ) {

		foreach( $this->get_meta_boxes() as $i => $box_data ) {
			if ( $id === $box_data['id'] ) {
				return $i;
			}
		}

		return -1;

	}


	/**
	 * Check if a meta box has been set
	 *
	 * @param $id
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function meta_box_exists( $id ) {

		return $this->locate_meta_box_index( $id ) >= 0;

	}


	/**
	 * Unset a meta box. Fails if meta boxes have been WP registered
	 *
	 * @param $id
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function unset_meta_box( $id ) {

		if ( $this->boxes_registered() ) {
			return FALSE;
		}

		if ( $this->meta_box_exists( $id ) ) {
			unset( $this->meta_boxes[ $this->locate_meta_box_index( $id ) ] );
		}

		return TRUE;

	}


	/**
	 * Return the data of a meta box by ID
	 *
	 * @param $id
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function get_meta_box_data( $id ) {

		$box_index = $this->locate_meta_box_index( $id );

		if ( $box_index < 0 ) {
			return array();
		}

		return $this->meta_boxes[ $box_index ];

	}


	/**
	 * To be called at the theme's setup action, e.g. `gft_setup`
	 *
	 * Where the `callback` argument option is a `tpl-` admin template reference
	 * the `callback_args` option can be used to send variables through to the
	 * template in the form of an associative array of key-value pairs.
	 *
	 *
	 * @param array $args
	 * @param int   $post_id
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function set_meta_box( array $args, $post_id = NULL ) {

		if ( empty( $args['id'] ) ) {
			return FALSE;
		}

		$this->unset_meta_box( $args['id'] );

		/*
		 * Fill in the optional args for add_meta_box so that
		 * we can pass args in any order and miss some out
		 * without causing the call to add_meta_box to fail
		 */
		$args = wp_parse_args( $args, array(
			'screen'        => NULL,
			'context'       => 'advanced',
			'priority'      => 'default',
			'callback_args' => NULL
		) );

		// Because the args will be pass as is to add_meta_box, we need to order them correctly
		$args_list = array(
			'id', 'title', 'callback', 'screen', 'context', 'priority', 'callback_args'
		);

		$args_buffer = array();
		foreach ( $args_list as $key ) {
			$args_buffer[ $key ] = $args[ $key ];
		}

		// Add the meta box to the registered list
		// todo Implement this variation of meta box
		$meta_box = array(
			'args' => $args_buffer
		);

		if ( $post_id !== NULL ) {
			$meta_box = array( 'post_id' => $post_id );
		}

		$this->meta_boxes[] = $args_buffer;

		return TRUE;

	}


	/**
	 * Return all meta boxes set on this instance
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function get_meta_boxes() {

		return $this->meta_boxes;

	}


	/**
	 * Echo a theme admin template part, to be used specifically as a callback with add_meta_box()
	 *
	 * @param string $post
	 * @param array  $data
	 * @param string $name
	 *
	 * @see add_meta_box
	 * @since 1.0.0
	 */
	public function _get_admin_template( $post, $data, $name = NULL ) {

		$args = array(
			$data['args'][0],
			$name,
			array(
				'Builder'  => $this,
				'meta_box' => $data
			)
		);

		// The second item of $data['args'] is expected to be vars to be passed to the template
		if ( ! empty( $data['args'][1] ) && is_array( $data['args'][1] ) ) {
			$args[2] += $data['args'][1];
		}

		call_user_func_array( 'gft_get_template_admin_part', $args );

	}


	/**
	 * During an admin save process use this to check the validity of passed data
	 *
	 * @param int          $post_id
	 * @param string      $option_id
	 * @param string|bool $post_type
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function verify_env_for_save( $post_id, $option_id, $post_type = FALSE ) {
		// Do nothing on autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return FALSE;
		}

		// Has a nonce been set?
		if ( ! isset( $_POST[ $option_id . '_nonce'] ) ) {
			return FALSE;
		}

		// If so, get it and check it
		$nonce = $_POST[ $option_id . '_nonce'];
		if ( ! wp_verify_nonce( $nonce, $option_id ) ) {
			return FALSE;
		}

		// Check that we're on the right post type
		if ( $post_type !== FALSE && $_POST['post_type'] !== $post_type ) {
			return FALSE;
		}

		// Check the current user's capabilities
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return FALSE;
		}

		return TRUE;
	}

}
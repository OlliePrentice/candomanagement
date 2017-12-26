<?php
namespace GFT\Admin;

/**
 * Class Notice
 *
 * @version 1.0.0
 * @package GFT\Admin
 */
class Notice {

	/**
	 * @var array Holds, briefly, any registered notices.
	 *            Will be blank on admin_init hook as WP redirects
	 *            after saving/updating a post
	 */
	static private $notices = array();

	/**
	 * @var string Notices are passed to the post-save/post-update
	 *             editing page via transients.
	 */
	static private $transient_id = '';


	/**
	 * Constructor
	 */
	public function __construct() {

		// Establish the transient ID using the theme's standard prefix
		self::$transient_id = STD_PREFIX . 'admin_notice';

		// Register notice actions to relevant hooks
		add_action( 'save_post', array( $this, 'save_notices' ), 11 );
		add_action( 'admin_init', array( $this, 'add_notices' ) );

	}


	/**
	 * Returns an instance of this class. Instantiates an instance if one doesn't exist
	 *
	 * @return Notice
	 */
	static public function get_instance() {

		global $Admin_Notice;

		if ( $Admin_Notice === NULL ) {
			$Admin_Notice = new Notice;
		}

		return $Admin_Notice;

	}


	/**
	 * Add a new or update an existing admin notice to be shown after a save or update
	 *
	 * @param string $notice_id      An identifier for the notice
	 * @param string $notice_message The notice message; may include HTML
	 * @param string $notice_class
	 * @param string $wrap
	 */
	static public function notice( $notice_id, $notice_message, $notice_class = 'error', $wrap = 'p' ) {

		if ( $wrap !== FALSE ) {
			$notice_message = sprintf( '<%1$s>%2$s</%1$s>', $wrap, $notice_message );
		}

		self::$notices[ $notice_id ] = array(
			'message' => $notice_message,
			'class'   => $notice_class . ' is-dismissible'
		);

	}


	/**
	 * If any notices have been registered, save them in a transient
	 */
	public function save_notices() {

		$notices = self::get_notices();

		if ( empty( $notices ) ) {
			return;
		}

		set_transient( self::$transient_id, $notices );

	}


	/**
	 * Return existing notices from either the cache or the instance of the class
	 *
	 * @param bool   $local     Defaults to `false`. If `false` checks for the cached version
	 *                          of the $notices property, `true` and it checks the instance's
	 *                          version of the property.
	 * @param string $notice_id The identifier for the notice
	 *
	 * @return array|bool An array of notices, `false` if no notices found
	 */
	static public function get_notices( $local = TRUE, $notice_id = '' ) {

		// Attempt to grab notices
		if ( $local === TRUE ) {
			$notices = self::$notices;

			// By default, the $notices property is an array
			if ( empty( $notices ) ) {
				$notices = FALSE;
			}

		} else {
			$notices = get_transient( self::$transient_id );
		}

		// No notices? Exit.
		if ( $notices === FALSE ) {
			return FALSE;
		}

		// Return, checking if a specific entry is required
		if ( $notice_id !== NULL && isset( $notices[ $notice_id ] ) ) {
			return $notices[ $notice_id ];
		} else {
			return $notices;
		}

	}


	/**
	 * Adds an `admin_notice` for each notice registered using this class
	 */
	public function add_notices() {

		$notices = self::get_notices( FALSE );
		if ( $notices === FALSE ) {
			return;
		} else {
			delete_transient( self::$transient_id );
		}

		foreach ( $notices as $notice_id => $notice ) {
			add_action( 'admin_notices', function() use ( $notice ) {

				echo '<div class="notice notice-'. $notice['class'] . '">';
				echo $notice['message'];
				echo '</div>';

			} );
		}

	}

}


/**
 * @return Notice
 */
function Admin_Notice() {
	return Notice::get_instance();
}


global $Admin_Notice;
$Admin_Notice = Admin_Notice();

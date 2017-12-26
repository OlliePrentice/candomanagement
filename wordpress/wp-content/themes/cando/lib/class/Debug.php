<?php
namespace GFT;

/**
 * Class Debug
 *
 * @version 1.0.0
 * @package GFT
 */
class Debug {

	/**
	 * Write a message to the debug log, usually found
	 * in wp-content/debug.log. In some cases, such as
	 * the value of $msg being an array or object, the
	 * log will not be written to.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param  string  $msg      The message to be written to the log
	 * @param  boolean $override Write to the log regardless of whether or not WP_DEBUG_LOG is set to TRUE
	 */
	public static function log( $msg, $override = FALSE ) {
		if ( WP_DEBUG_LOG === TRUE || $override === TRUE ) {
			@error_log( $msg );
		}
	}


	/**
	 * With the given $output, either log to the configured error log or print to the screen based on
	 * the given arguments.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param mixed     $output The data you wish to review
	 * @param bool|null $log    Whether or not to send the $output to the configured error log
	 * @param bool|null $print  Whether or not to print the $output to the page
	 */
	public static function debug( $output, $log = NULL, $print = NULL ) {
		// If $output is an array or object, convert it to a string
		if ( is_array( $output ) || is_object( $output ) ) {
			$output = print_r( $output, TRUE );
		}

		// If $output is boolean, convert it to a string
		if ( is_bool( $output ) ) {
			$prefix = '(bool) ';
			$value  = ( $output ) ? 'true' : 'false';
			$output = $prefix . $value;
		}

		// If $output is NULL, convert it to a string
		if ( $output === NULL ) {
			$output = 'NULL';
		}

		// Print the output to the page?
		if ( self::get_env_setting( $print, 'WP_DEBUG_DISPLAY' ) ) {
			echo '<pre>' . $output . '</pre>';
		}

		// Log the output if required via either the code or the environment
		if ( self::get_env_setting( $log, 'WP_DEBUG_LOG' ) === TRUE ) {
			self::log( $output );
		}
	}


	/**
	 * Kills all script execution at the point that this method
	 * is called and sends $output to the error log.
	 *
	 * By setting the $print parameter to TRUE, the output can be sent to
	 * the screen too. $output will also be printed to screen if
	 * WP_DEBUG_DISPLAY is set to TRUE.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param  mixed   $output The text that will be output in the error log, and on screen if $print is TRUE
	 * @param  boolean $print  Set to TRUE to print the output to screen
	 */
	public static function kill( $output, $print = NULL ) {
		// $print param should be a boolean value
		if ( ! is_bool( $print ) && ! is_null( $print ) ) {
			self::debug( __METHOD__ . ' expects second parameter to be NULL or boolean, ' . gettype( $print ) . ' given', TRUE );
		}

		// Decide whether or not to print to page based on the given option/environment variables
		$print = self::get_env_setting( $print, 'WP_DEBUG_DISPLAY' );

		// The output is logged regardless of environment
		self::debug( $output, TRUE, $print );

		// Kill
		   die;
	}


	/**
	 * Declare an error
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param  int    $level The error level
	 * @param  string $msg   The error message/details to be written to the page and the error log
	 */
	public static function error( $level, $msg ) {
		// Set error levels
		$levels = array(
			1 => 'fatal',
			2 => 'warning',
			3 => 'notice'
		);

		$msg = '<b>' . $levels[ $level ] . '</b>: ' . $msg;

		// Kill script if fatal error
		if ( $level === 1 ) {
			self::kill( $msg );
		}

		// Print error message to page for any other error
		if ( WP_DEBUG_DISPLAY === TRUE ) {
			self::debug( $msg );
		}

		// Log error if environment permits
		if ( WP_DEBUG_LOG === TRUE ) {
			self::log( strip_tags( $msg ) );
		}
	}


	/**
	 * An easier way to conditionally override a boolean constant within a method. Only useful
	 * with boolean values. Methods using this method are typically passing an argument
	 * directly from itself to this.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 *
	 * @param bool   $given   The setting given for the environment variable
	 * @param string $env_var The environment variable, usually a constant, e.g. WP_DEBUG_LOG
	 */
	private static function get_env_setting( $given, $env_var ) {
		// Kill script if given constant doesn't exist
		if ( constant( $env_var ) === NULL ) {
			throw new \Exception( 'Given constant <code>' . $env_var . '</code> doesn\'t exist in ' . __METHOD__ );
		}

		// Default to non-action
		$feedback = FALSE;

		// If the given environment var is turned on, action is a go
		if ( constant( $env_var ) === TRUE ) {
			$feedback = TRUE;
		}

		// If $given isn't null (i.e. has been set explicitly), it overrides $env_var
		if ( ! is_null( $given ) && is_bool( $given ) ) {
			$feedback = $given;
		}

		return $feedback;
	}

} // END class
<?php
namespace GFT;

/**
 * Class SVG
 *
 * @version 1.0.0
 * @package GFT
 */
if ( ! class_exists( 'SVG' ) ) {
/**
 * Class SVG
 *
 * @version 1.0.0
 */
class SVG {

	protected $loaded = array();

	protected $default_dir;

	protected $default_url;

	protected $default_path = '/assets/images';

	private $_output_wrap_id = 'svg_all_container';

	private $_output_script_written = false;


	/**
	 * @param null $svg_dir_path
	 *
	 * todo Check that the path given in the parameter exists, throw exception otherwise
	 */
	public function __construct( $svg_dir_path = NULL ) {
		if ( empty( $svg_dir_path ) ) {
			$svg_dir_path = $this->default_path;
		}

		// Default SVG path/URL
		$this->default_dir = get_stylesheet_directory() . $svg_dir_path;
		$this->default_url = get_stylesheet_directory_uri() . $svg_dir_path;

		add_action( 'wp_footer', array( $this, 'output_all' ), 20 );
	}


	/**
	 * @param $src
	 *
	 * @return bool|array
	 *
	 * todo Reliably check WordPress root directory
	 */
	public function locate( $src ) {
		// Check for starting slash, remove if exists
		if ( strpos( $src, '/' ) === 0 ) {
			$src = substr( $src, 1 );
		}

		$data = FALSE;

		// Check default path
		if ( file_exists( $this->default_dir . '/' . $src . '.svg' ) ) {
			$data = array(
				'path' => $this->default_dir . '/' . $src . '.svg',
				'src'  => $this->default_url . '/' . $src . '.svg'
			);
		}

		// Check relative to theme directory
		if ( file_exists( get_stylesheet_directory() . '/' . $src . '.svg' ) ) {
			$data = array(
				'path' => get_stylesheet_directory() . '/' . $src . '.svg',
				'src'  => get_stylesheet_directory_uri() . '/' . $src . '.svg'
			);
		}

		// Return result
		return $data;
	}


	/**
	 * @param      $id
	 * @param null $src
	 *
	 * @return bool
	 *
	 * todo Look at grabbing the ID from the SVG file itself
	 */
	public function register( $id, $src = NULL ) {
		// The ID can't have slashes
		if ( strpos( $id, '/' ) ) {
			return FALSE;
		}

		// Check that the SVG hasn't already been loaded
		if ( isset( $this->loaded[ $id ] ) ) {
			return FALSE;
		}

		// Check that the file given exists
		if ( is_null( $src ) ) {
			$svg_data = $this->locate( $id );
		} else {
			$svg_data = $this->locate( $src );
		}

		if ( $svg_data === FALSE ) {
			return FALSE;
		}

		// Add SVG to list of registered SVGs
		$this->loaded[ $id ] = $svg_data + array(
			'code' => file_get_contents( $svg_data['path'] )
		);

		return TRUE;
	}


	/**
	 * Writes all registered SVGs inline to the page and ensures they're moved to the top
	 * of the HTML structure for the benefit of <use> calls later in the page
	 */
	public function output_all() {
		echo '<div id="' . $this->_output_wrap_id . '">';
		$this->output();
		echo '</div>';
		$this->_write_move_output_script();
	}


	/**
	 * Echoes out, in place, the SVG as inline code
	 *
	 * @param string $id File location or registered ID
	 */
	public function output( $id = NULL, $atts = array() ) {
		if ( empty( $id ) ) {
			echo '<div style="display:none;">';
			foreach ( $this->loaded as $data ) {
				echo $data['code'];
			}
			echo '</div>';

		} elseif ( ! empty( $id ) ) {
			$output = '';

			// Given ID has been registered
			if ( isset( $this->loaded[ $id ] ) ) {
				$output .= $this->loaded[ $id ]['code'];

				// Given ID not registered, attempt as if file name given
			} else {
				if ( $this->locate( $id ) ) {
					// todo Implement this line when there's time to test
					//$this->register( $id );

					$svg_data = $this->locate( $id );
					$output  .= gft_strip_attr( 'id', file_get_contents( $svg_data['path'] ) );
					//$output  .= file_get_contents( $svg_data['path'] );
				} else {
					echo '';
				}
			}

			// Add in given attribute data
			if ( is_array( $atts ) && ! empty( $atts ) ) {
				$attr_str = '';

				foreach ( $atts as $attr => $val ) {
					$attr_str .= ' ' . $attr . '="' . $val . '"';
				}

				$output = str_replace( '<svg', '<svg' . $attr_str, $output );
			}

			echo $output;

		} else {
			echo '';
		}
	}


	/**
	 * Attempts to `use` a registered SVG
	 *
	 * @param $id
	 * @param $atts
	 */
	public function load( $id, $atts = array() ) {
		if ( isset( $this->loaded[ $id ] ) ) {
			if ( is_array( $atts ) && ! empty( $atts ) ) {
				$output = '<svg';
				foreach ( $atts as $attr => $val ) {
					$output .= ' ' . $attr . '="' . $val . '"';
				}
				$output .= '>';

			} else {
				$output = '<svg>';
			}

			$output .= '<use xlink:href="#svg_' . $id . '" xmlns:xlink="http://www.w3.org/1999/xlink"></use>';
			$output .= '</svg>';
			echo $output;
		} else {
			if ( $this->register( $id ) ) {
				$this->load( $id, $atts );
			} else {
				echo '';
			}
		}
	}


	/**
	 * @param $id
	 *
	 * @return array
	 */
	public function get( $id ) {
		if ( isset( $this->loaded[ $id ] ) ) {
			return $this->loaded[ $id ];
		} else {
			return $this->locate( $id );
		}
	}


	/**
	 * Writes some JS to the page that moves the 'all' output to the top of the page
	 *
	 * So that we can capture all registered SVGs the `output_all()` isn't run until
	 * wp_footer. However, inline SVG needs to be at the top of the HTML structure if
	 * we want to be able to `use` it later on the page. This moves it from the foot
	 * of the page to the head, assuming jQuery is loaded.
	 */
	private function _write_move_output_script() {
		if ( $this->_output_script_written === true ) {
			return;
		}

		echo '<script type="text/javascript">';
		echo <<<JAVASCRIPT
(function($) {
	$('#$this->_output_wrap_id').prependTo('body');
})(jQuery);
JAVASCRIPT;
		echo '</script>';

		$this->_output_script_written = true;
	}

} // END class
} // END class_exists


global $GFT_SVG;
$GFT_SVG = new SVG;
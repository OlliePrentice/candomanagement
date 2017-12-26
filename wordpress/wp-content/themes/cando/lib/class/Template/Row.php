<?php
namespace GFT\Template;

/**
 * Row Builder
 *
 * @package GFT\Template
 */
class Row {

	/**
	 * Maximum number of columns allowed in a row
	 */
	const COLUMN_MAX = 12;

	/**
	 * Standard CSS class name for a row
	 */
	const STD_ROW_CLASS = 'tpl-row';

	/**
	 * Standard background colour for row columns
	 */
	const STD_COL_BG_COLOUR = '#f1f1f1';


	/**
	 * @var array
	 */
	private $column_sizes;

	/**
	 * @var array
	 */
	private $column_content = array();


	/**
	 * Constructor
	 *
	 * @param array $column_sizes
	 *
	 * @throws \Exception
	 */
	public function __construct( Array $column_sizes ) {

		// All values of $column_sizes must be integer
		array_walk( $column_sizes, function( $v ) {
			if ( ! is_int( $v ) ) {
				throw new \Exception( __CLASS__ . '::column_sizes expects all values to be integer, ' . gettype( $v ) . ' given' );
			}
		} );

		// Accommodate for cases where one size is given
		if ( count( $column_sizes ) === 1 ) {
			if ( $column_sizes[0] === 1 ) {
				// 1 === Full width
				$column_sizes[0] = self::COLUMN_MAX;

			} elseif( $column_sizes[0] !== self::COLUMN_MAX && self::COLUMN_MAX % $column_sizes[0] === 0 ) {
				$column_size = $column_sizes[0];

				// We've checked that the max is a multiple of the size given (using modulus)
				// Now, a bit more math to find out how many columns there should be
				for ( $i = 0; $i < ( self::COLUMN_MAX / $column_size ); $i++ ) {
					$column_sizes[ $i ] = $column_size;
				}
			}
		}

		// All values of $column_sizes can not total more than 12 (the total grid size)
		$total_columns_size = 0;
		foreach ( $column_sizes as $column_size ) {
			$total_columns_size += $column_size;
		}

		if ( $total_columns_size !== self::COLUMN_MAX ) {
			throw new \Exception( 'Column sizes given do not equate to total column size of ' . self::COLUMN_MAX );
		}

		// Save sizes with indexes, starting from 1
		$index = 0;
		$this->column_sizes = array();

		foreach ( $column_sizes as $col_size ) {
			$index++;
			$this->column_sizes[ $index ] = $col_size;
		}

	}


	/**
	 * Get CSS class associated with given column size
	 *
	 * @param int $column_size
	 *
	 * @return string
	 */
	public function get_size_class( $column_size ) {

		if ( $column_size === self::COLUMN_MAX ) {
			return 'full-width';

		} elseif ( $column_size > 1 && $column_size < self::COLUMN_MAX ) {
			return 'col-' . $column_size;

		} else {
			return '';
		}

	}


	/**
	 * Get CSS class associated with given type
	 *
	 * @param $type
	 *
	 * @return string
	 */
	public function get_type_class( $type ) {

		switch ( $type ) {
			case 'txt':
				return 'txt';
				break;

			case 'img':
			case 'hero':
				return 'img';
				break;

			case 'img_subtext':
				return 'img-subtext';
				break;

			case 'img_overlay':
				return 'img-overlay';
				break;

			case 'vid_preview':
				return 'vid-preview img';
				break;

			default:
				return $type;
				break;
		}

	}


	/**
	 * Get the column type from the given preset name
	 *
	 * @param $preset_name
	 *
	 * @return string
	 */
	protected function get_type_by_preset_name( $preset_name ) {

		switch ( $preset_name ) {
			case 'html':
			case 'txt_w_heading':
				return 'txt';
				break;

			case 'img_only':
			case 'hero':
				return 'img';
				break;

			case 'img_subtext':
				return 'img_subtext';

			case 'img_w_overlay':
				return 'img_overlay';
				break;

			case 'vid_preview':
				return 'vid_preview';
				break;

			default:
				return '';
				break;
		}

	}


	/**
	 * Check if a column index exists
	 *
	 * @param int $index
	 *
	 * @return bool
	 */
	public function index_exists( $index ) {

		return array_key_exists( $index, $this->column_sizes );

	}


	/**
	 * Check if a column has been content set (empty content may be desirable)
	 *
	 * @param int $index
	 *
	 * @return bool
	 */
	public function is_content_set( $index ) {

		return array_key_exists( $index, $this->column_content );

	}


	/**
	 * If the URL given is relative this makes that URL absolute
	 *
	 * @param string $img_url
	 *
	 * @return string|void
	 */
	public function complete_img_url( $img_url ) {

		$final_url = $img_url;
		if ( strpos( $img_url, 'http' ) !== 0 ) {
			$final_url = gft_get_asset( $img_url, 'img' );
		}

		return $final_url . '?v=' . CHILD_THEME_VERSION;

	}


	/**
	 * Set values on a column item by index
	 *
	 * @param $index
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public function set_content_value( $index, $key, $value = NULL ) {

		if ( ! $this->index_exists( $index ) ) {
			return FALSE;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $k => $v ) {
				$this->column_content[ $index ][ $k ] = $v;
			}

		} else {
			$this->column_content[ $index ][ $key ] = $value;
		}

		return TRUE;

	}


	/**
	 * Get a value from a set column by index
	 *
	 * @param $index
	 * @param $key
	 *
	 * @return bool
	 */
	public function get_content_value( $index, $key ) {

		if ( ! $this->index_exists( $index ) ) {
			return FALSE;
		}

		return $this->column_content[ $index ][ $key ];

	}


	/**
	 * Add arbitrary content to a column by index
	 *
	 * @param int    $index
	 * @param string $content
	 * @param string $type
	 *
	 * @return bool
	 */
	public function set_content_on( $index, $type, $content ) {

		if ( ! $this->index_exists( $index ) ) {
			return FALSE;
		}

		// Set that index
		$this->set_content_value( $index, array(
			'content' => $content,
			'type'    => $type
		) );

		return TRUE;

	}


	/**
	 * Set content on a column using a preset HTML template
	 *
	 * @param       $index
	 * @param       $preset_name
	 * @param array $preset_args
	 *
	 * @return bool
	 */
	public function set_preset_on( $index, $preset_name, Array $preset_args ) {

		if ( ! $this->index_exists( $index ) ) {
			return FALSE;
		}

		$content = $this->get_col_preset( $preset_name, $preset_args );
		$type    = $this->get_type_by_preset_name( $preset_name );

		return $this->set_content_on( $index, $type, $content );

	}


	/**
	 * Set the background colour for a column by index
	 *
	 * @param $index
	 * @param $css_colour
	 *
	 * @return bool
	 */
	public function set_bg_colour_on( $index, $css_colour ) {

		if ( ! $this->index_exists( $index ) ) {
			return FALSE;
		}

		$this->set_content_value( $index, 'bg_colour', $css_colour );

		return TRUE;

	}


	/**
	 * Get the background colour set for a column by index
	 *
	 * @param $index
	 *
	 * @return string|bool
	 */
	public function get_bg_colour_on( $index ) {

		if ( ! $this->index_exists( $index ) ) {
			return '';
		}

		return isset( $this->column_content[ $index ]['bg_colour'] ) ? $this->column_content[ $index ]['bg_colour'] : '';

	}


	/**
	 * Build a column's HTML and return it
	 *
	 * @param $index
	 *
	 * @return string
	 */
	public function get_col( $index ) {

		if ( ! $this->index_exists( $index ) ) {
			return '';
		}

		$type_class = $this->get_type_class( $this->column_content[ $index ]['type'] );
		$size_class = $this->get_size_class( $this->column_sizes[ $index ] );
		$content    = $this->column_content[ $index ]['content'];
		$item_class = self::STD_ROW_CLASS . '__item';

		// Items that are image based should always have the `img` CSS class
		if ( strpos( $type_class, 'img-' ) !== FALSE || strpos( $type_class, '-img' ) !== FALSE ) {
			$type_class .= ' img';
		}

		$style     = '';
		$bg_colour = $this->get_bg_colour_on( $index );

		// Get background colour
		if ( $bg_colour !== '' ) {
			if ( $bg_colour !== FALSE ) {
				$style .= 'background-color:' . $this->get_bg_colour_on( $index ) . ';';
			}

		} else {
			if ( $this->get_content_value( $index, 'type' ) === 'txt' ) {
				$style .= 'background-color:' . self::STD_COL_BG_COLOUR . ';';
			}
		}

		if ( $style !== '' ) {
			$style_attr = 'style="' . $style . '"';
		} else {
			$style_attr = '';
		}

		return <<<HTML
<div class="$item_class $type_class $size_class" $style_attr>$content</div>
HTML;

	}


	/**
	 * Takes the content set on the columns and renders the entire row
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function build( $atts = array() ) {

		$atts = wp_parse_args( $atts, array(
			'class' => self::STD_ROW_CLASS
		) );

		// Make sure that the row class is on the row element
		if ( strpos( $atts['class'], self::STD_ROW_CLASS ) === FALSE ) {
			$atts['class'] .= ' ' . self::STD_ROW_CLASS;
		}

		// Check for a row ID
		$row_id = NULL;
		if ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) {
			$row_id = $atts['id'];
		}

		// Ensure the columns are in index order
		ksort( $this->column_content );

		// Loop through the columns and get the HTML for each
		$columns_output = '';
		foreach ( $this->column_content as $index => $column ) {
			$columns_output .= $this->get_col( $index );
		}

		// If an ID attr has been set, use that with the genesis attr builder
		$context = self::STD_ROW_CLASS;
		if ( ! empty( $row_id ) ) {
			$context .= '-' . $row_id;
		}

		$final_output  = sprintf( '<div %s>', genesis_attr( $context, $atts ) );
		$final_output .= $columns_output;
		$final_output .= '</div>';

		return $final_output;

	}


	/**
	 * Check an array of row values against a page layout and fills in missing values with empty strings
	 *
	 * Unexpected row values are removed
	 *
	 * @param array $page_layout
	 * @param array $row_values
	 *
	 * @return array
	 */
	static public function parse_row_values( Array $page_layout, Array $row_values ) {

		foreach ( $page_layout as $i => $row ) {
			// If a row hasn't been filled in, loop through and all empty strings for all possible fields
			if ( ! isset( $row_values[ $i ] ) ) {
				foreach ( $row as $row_data ) {
					$row_values[ $i ][ $row_data['name'] ] = '';
				}

			} else {
				if ( count( $row_values[ $i ] ) !== count( $row ) ) {
					// Loop through the data and fill in the missing values
					foreach ( $row as $row_data ) {
						if ( ! array_key_exists( $row_data['name'], $row_values[ $i ] ) ) {
							$row_values[ $i ][ $row_data['name'] ] = '';
						}
					}
				}

				// Check for and remove any row components that aren't expected
				foreach ( $row_values[ $i ] as $component_name => $component_value ) {
					$component_is_expected = FALSE;

					foreach ( $row as $component_data ) {
						if ( in_array( $component_name, $component_data ) ) {
							$component_is_expected = TRUE;
						}
					}

					if ( $component_is_expected === FALSE ) {
						unset( $row_values[ $i ][ $component_name ] );
					}
				}
			}
		}

		return $row_values;

	}


	/**
	 * Used to run HTML presets programmatically
	 *
	 * @param string $preset_name
	 * @param array  $preset_args
	 *
	 * @return mixed
	 */
	public function get_col_preset( $preset_name, Array $preset_args ) {

		return call_user_func_array( array( $this, 'col_preset_' . $preset_name ), $preset_args );

	}


	/**
	 * Basic column, accepts HTML
	 *
	 * @param $html
	 *
	 * @return string
	 */
	public function col_preset_html( $html ) {

		$wrap_class = self::STD_ROW_CLASS . '__txt-wrap';
		$cont_class = self::STD_ROW_CLASS . '__txt-content';

		return <<<HTML
<div class="$wrap_class">
	<div class="$cont_class">
		$html
	</div>
</div>
HTML;

	}


	/**
	 * Column with a heading and some text (text can just be HTML)
	 *
	 * @param string $heading
	 * @param string $txt
	 *
	 * @return string
	 */
	public function col_preset_txt_w_heading( $heading, $txt ) {

		$wrap_class = self::STD_ROW_CLASS . '__txt-wrap';
		$cont_class = self::STD_ROW_CLASS . '__txt-content';

		$heading_html = $this->html_std_heading( $heading );

		return <<<HTML
<div class="$wrap_class">
	<div class="$cont_class">
		$heading_html
		$txt
	</div>
</div>
HTML;

	}


	/**
	 * Column that is background image, optional HTML within element with background image
	 *
	 * @param string $bg_img
	 * @param string $html
	 *
	 * @return string
	 */
	public function col_preset_img_only( $bg_img, $html = '' ) {

		$bg_img     = $this->complete_img_url( $bg_img );
		$wrap_class = self::STD_ROW_CLASS . '__img-wrap';

		return <<<HTML
<div class="$wrap_class" style="background-image: url('$bg_img')">$html</div>
HTML;

	}


	/**
	 * Column, hero style
	 *
	 * @param string $bg_img       The column background image
	 * @param string $html         Accepts both heading text or arbitrary HTML
	 * @param bool   $heading_only If true `$html` is wrapped in heading HTML; if false `$html` is left alone
	 *
	 * @return string
	 */
	public function col_preset_hero( $bg_img, $html = '', $heading_only = TRUE ) {

		$bg_img      = $this->complete_img_url( $bg_img );
		$wrap_class  = self::STD_ROW_CLASS . '__img-wrap ';
		$wrap_class .= self::STD_ROW_CLASS . '__hero';

		if ( $heading_only === TRUE ) {
			$html = $this->html_hero_heading( $html );
		}

		return <<<HTML
<div class="$wrap_class" style="background-image: url('$bg_img')">$html</div>
HTML;

	}


	/**
	 * Column with background image and overlay, optional heading and HTML goes over overlay
	 *
	 * @param string $bg_img
	 * @param string $heading
	 * @param string $html
	 *
	 * @return string
	 */
	public function col_preset_img_w_overlay( $bg_img, $heading = '', $html = '' ) {

		$bg_img            = $this->complete_img_url( $bg_img );
		$wrap_class        = self::STD_ROW_CLASS . '__img-wrap';
		$overlay_class     = self::STD_ROW_CLASS . '__img-overlay';
		$img_content_class = self::STD_ROW_CLASS . '__img-content';
		$content           = '';

		if ( $heading !== '' || $html !== '' ) {
			$content .= '<div class="' . $img_content_class . '">';

			if ( $heading !== '' ) {
				$content .= $this->html_std_heading( $heading, 'h3' );
			}

			if ( $html !== '' ) {
				$content .= $html;
			}

			$content .= '</div>';
		}

		return <<<HTML
<div class="$wrap_class" style="background-image: url('$bg_img')">
	<div class="$overlay_class"></div>
	$content
</div>
HTML;

	}


	/**
	 * Get HTML for standard column heading
	 *
	 * @param string $title
	 * @param string $tag
	 * @param array  $attr
	 *
	 * @return string
	 */
	public function html_std_heading( $title, $tag = NULL, Array $attr = array() ) {

		// Default heading tag is `h2`
		if ( empty( $tag) ) {
			$tag = 'h2';
		}

		// Build attribute string
		$attr = genesis_attr( self::STD_ROW_CLASS . '__heading', $attr );

		return <<<HTML
<$tag $attr>$title</$tag>
HTML;

	}


	/**
	 * Get HTML for hero style heading
	 *
	 * @param string $title
	 * @param string $tag
	 * @param array  $attr
	 *
	 * @return string
	 */
	public function html_hero_heading( $title, $tag = NULL, Array $attr = array() ) {

		$heading_class = self::STD_ROW_CLASS . '__heading hero center';

		if ( isset( $attr['class'] ) ) {
			$attr['class'] .= ' ' . $heading_class;
		} else {
			$attr['class'] = $heading_class;
		}

		return $this->html_std_heading( $title, $tag, $attr );

	}

}

<?php
namespace GFT\Admin;

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

/**
 * Class CarbonFields
 *
 * @version 1.0.0
 * @package GFT\Admin
 */
class CarbonFields {

	/**
	 * @var   array
	 * @since 1.0.0
	 */
    private $containers = array();

	/**
	 * @var   array
	 * @since 1.0.0
	 */
    private $tabs = array();


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
    }


    /**
     * Check Carbon Fields is up and running
     *
     * @return bool
	 * @since 1.0.0
     */
    private function preflight_check() {

        if ( !class_exists('Carbon_Fields\Container\Container') && !class_exists('Carbon_Fields\Container\Field') ) {
            return false;
        } else {
            return true;
        }

    }


    /**
     * Create a new container
     *
     * @param $key
     * @param $type
     * @param $title
     * @return bool|mixed
	 * @since 1.0.0
     */
    public function setContainer( $key, $type, $title ) {

        // Check Carbon Fields is up and running
        if ( !$this->preflight_check() )
            return false;

        // Check container doesn't exists
        if ( isset($this->containers[$key]) )
            return false;

        // Add to the containers list
        $this->containers[$key] = Container::make( $type, $title );

        // Return the new container object
        return $this->containers[$key];
    }


    /**
     * Get an existing container
     *
     * @param $key
     * @return bool|mixed
     */
    public function getContainer( $key ) {

        // Check container exists first
        if ( !isset($this->containers[$key]) )
            return false;

        return $this->containers[$key];

    }

    /**
     *
     * @param $container
     * @param $title
     * @param $fields
     * @return bool
	 * @since 1.0.0
     */
    public function setTab( $container, $title, $fields ) {

        // Check Carbon Fields is up and running
        if ( !$this->preflight_check() )
            return false;

        // Check container exists first
        if ( !isset($this->containers[$container]) )
            return false;

        // Add tab to the container
        $this->containers[$container]->add_tab( $title, $fields );

        return true;
    }



    public function container( $type, $title ) {

        // Check Carbon Fields is up and running
        if ( !$this->preflight_check() )
            return false;

        return Container::make( $type, $title );

    }

    public function field( $type, $name, $label = null ) {

        // Check Carbon Fields is up and running
        if ( !$this->preflight_check() )
            return false;

        return Field::make( $type, $name, $label );
    }
}

global $GFT_CF;
$GFT_CF = new CarbonFields;

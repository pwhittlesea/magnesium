<?php
/*
 * Main Magnesium Class
 *
 * Author: Phillip Whittlesea <pw.github@thega.me.uk>
 * Date: 12/05/2012
 */
include_once(dirname ( __FILE__ ) . "/lib/arc/ARC2.php");
include_once(dirname ( __FILE__ ) . "/config/arc_config.php");
include_once(dirname ( __FILE__ ) . "/config/mag_config.php");

include_once(dirname ( __FILE__ ) . "/classes/Magnesium_Single.php");

class Magnesium {

    // Reference to store
    protected static $store;

    // List of ns to use as SPARQL PREFIX
    protected $namespaces;

    /**
     * __construct
     * Private constructor function
     */
    function __construct() {
        global $arc_config, $mag_config;

        // Initialise the ARC2 Store
        $this->store = ARC2::getStore($arc_config);
        if (!$this->store->isSetUp()) $this->store->setUp();

        // Populate ns store from config
        $this->namespaces = array();
        foreach( $mag_config['namespaces'] as $short => $long ) {
            $this->ns( $short, $long );
        }
    }

    /**
     * ns
     * Add namespace to global store
     *
     * @param string $short namespace prefix
     * @param string $long full namespace qualifier
     *
     * @return boolean true
     */
    public function ns( $short = "blank", $long = "http://example.com/" ) {
        $this->namespaces[ $short ] = $long;
        return true;
    }

    /**
     * nsToString
     * Return SPARQL PREFIX list
     *
     * @return string $pl
     */
    private function nsToString() {
        $pl = "";
        foreach( $this->namespaces as $nss => $nsl ) {
            $pl .= "PREFIX ${nss}: <${nsl}>\n";
        }
        return $pl;
    }

    /**
     * query
     * Query local ARC2 store for information
     *
     * @param string $query Query to run
     *
     * @return array rows from ARC2 store
     * @return null
     */
    protected function query( $query = null ) {
        if ($rows = $this->store->query($this->nsToString().$query, 'rows')) {
            return $rows;
        }
        return null;
    }

    /**
     * get
     * Get subject
     *
     * @param string $uri The id of the subject
     *
     * @return Magnesium_Single Object representing $uri
     */
    public function get( $uri = null ) {
        return new Magnesium_Single( $this, $uri );
    }
}

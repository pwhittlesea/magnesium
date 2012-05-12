<?php
/*
 * Author: Phillip Whittlesea <pw.github@thega.me.uk>
 * Date: 12/05/2012
 */
include_once(dirname ( __FILE__ ) . "/lib/arc/ARC2.php");
include_once(dirname ( __FILE__ ) . "/config/arc_config.php");
include_once(dirname ( __FILE__ ) . "/config/mag_config.php");

include_once(dirname ( __FILE__ ) . "/classes/Magnesium_Single.php");

class Magnesium {

    protected static $store;
    protected $namespaces;

    // private constructor function
    function __construct() {
        global $arc_config, $mag_config;

        $this->store = ARC2::getStore($config);
        if (!$this->store->isSetUp()) $this->store->setUp();

        $this->namespaces = array();

        foreach( $config['namespaces'] as $short => $long ) {
            $this->ns( $short, $long );
        }
    }

    function ns( $short = "blank", $long = "http://example.com/" ) {
        $this->namespaces[ $short ] = $long;
    }

    private function nsToString() {
        $string = "";
        foreach( $this->namespaces as $nss => $nsl ) {
            $string .= "PREFIX ${nss}: <${nsl}>\n";
        }
        return $string;
    }

    public function query( $query = null ) {
        if ($rows = $this->store->query($this->nsToString().$query, 'rows')) {
            return $rows;
        }
        return false;
    }

    public function get( $uri = null ) {
        return new Magnesium_Single( $this, $uri );
    }
}

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

    // List of ns to use as PREFIX
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
     * Return PREFIX list
     *
     * @param boolean $ttl is the string to be ttl compatible
     *
     * @return string $pl
     */
    private function nsToString( $ttl = false ) {
        $pl = "";
        $prefix = ($ttl) ? "@prefix" : "PREFIX";

        foreach( $this->namespaces as $nss => $nsl ) {
            $pl .= "${prefix} ${nss}: <${nsl}>\n";
        }
        return $pl;
    }

    /**
     * query
     * Query local ARC2 store for information
     *
     * @param string $query Query to run
     * @param string $type return type
     *
     * @return array rows from ARC2 store
     * @return null
     */
    public function query( $query = null, $type = 'rows') {
        $return = array();

        if ($rows = $this->store->query($this->nsToString().$query, $type)) {
            $return = $rows;
        }
        if ($errs = $this->store->getErrors()) {
            foreach ($errs as $err) {
                echo "Error: ${err}\n";
            }
        }
        return $return;
    }

    /**
     * select
     * Query local ARC2 store using SELECT query
     *
     * @param string $subject subject to restrict
     * @param string $predicate predicate to restrict
     * @param string $object object to restrict
     * @param string $wanted component of query desired
     * @param string $graph sub-graph to query
     *
     * @return array rows from ARC2 store
     */
    public function select( $subject = null, $predicate = null, $object = null, $wanted = null, $graph = null ) {
        $g = ($graph) ? '<'.$graph.'>' : '?g';
        $s = ($subject) ? '<'.$subject.'>' : '?s';
        $p = ($predicate) ? $predicate : '?p';
        $o = ($object) ? '<'.$object.'>' : '?o';
        $w = ($wanted) ? $wanted : '?o';

        return $this->query("SELECT ${w} WHERE { GRAPH ${g} { ${s} ${p} ${o} . } }");
    }

    /**
     * insert
     * Insert new data into local ARC2 store
     *
     * @param string $s subject to insert
     * @param string $p predicate to insert
     * @param string $o object to insert
     * @param string $graph sub-graph to insert to
     *
     * @return response from ARC2 store
     */
    public function insert( $s, $p, $o, $graph = "http://example.com" ) {

        // Initialise the ARC2 Parser
        $parser = ARC2::getTurtleParser();

        $data = $this->nsToString(true) . "${s} ${p} ${o} .";
        $parser->parse($graph, $data);
        return $this->store->insert($parser->getTriples(), $graph, 0);
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

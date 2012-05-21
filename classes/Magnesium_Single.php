<?php
/*
 * Subject single
 *
 * Author: Phillip Whittlesea <pw.github@thega.me.uk>
 * Date: 12/05/2012
 */

class Magnesium_Single {

    // The identifier for the Subject
    private static $uri;
    // Link to Magnesium master object
    private static $magnesium;

    /**
     * __construct
     * Private constructor function
     *
     * @param string $magnesium Master object
     * @param string $uri new Subject id
     */
    function __construct($magnesium = null, $uri = null) {
        $this->magnesium = $magnesium;
        $this->uri = $uri;
    }

    /**
     * rels
     * Fetch list of objects using predicate
     *
     * @param string $predicate predicate to use
     *
     * @return array Magnesium_Single objects
     */
    public function rels($predicate = '') {
        $o = array();
        if ($rows = $this->magnesium->select( $this->uri, $predicate, null, '?o' )) {
            foreach ($rows as $row) {
                array_push( $o , $this->magnesium->get( $row['o'] ));
            }
        }
        return $o;
    }

    /**
     * rel
     * Fetch object using predicate
     *
     * @param string $predicate predicate to use
     *
     * @return Magnesium_Single object
     */
    public function rel($link = '') {
        $rels = $this->rels($link);
        if( count($rels) > 0 ) return $rels[0];
        return null;
    }

    /**
     * type
     * Fetch type of object
     *
     * @return Magnesium_Single type
     */
    public function type() {
        return $this->rel( "rdf:type" );
    }

    /**
     * type
     * Fetch label of object
     *
     * @return String rdfs:label of object
     */
    public function __toString() {
        $l = $this->rel( "rdfs:label" );
        if($l) return (string) $l;
        return (string) $this->uri;
    }
}

<?php
/*
 * Author: Phillip Whittlesea <pw.github@thega.me.uk>
 * Date: 12/05/2012
 */
 
class Magnesium_Single { 

    private $url;
    private $magnesium;

    // private constructor function 
    function __construct($magnesium = null, $uri = null) { 
        $this->magnesium = $magnesium;
        $this->url = $uri;
    } 
    
    public function rels($link = '') {
        $query = "SELECT * WHERE { <".$this->url."> ".$link." ?b . }";

        $o = array();
        if ($rows = $this->magnesium->query($query)) {
            foreach ($rows as $row) {
                array_push( $o , $this->magnesium->get( $row['b'] ));
            }
        }
        return $o;
    }

    public function rel($link = '') {
        $rels = $this->rels($link);
        if( count($rels) > 0 ) return $rels[0];
        return null;
    }

    public function type() {
        return $this->rel( "rdf:type" );
    }

    public function __toString() {
        $l = $this->rel( "rdfs:label" );
        if($l) return (string) $l;
        return (string) $this->url;
    }
}

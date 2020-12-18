<?php

namespace BS\IC\resource;

/**
 * @author BourneSuper
 *
 */
class Radio{
    
    public static $BAND;
    
    public function __construct( ){
        self::$BAND = 'init BAND';
    }
    
    /**
     * 
     */
    public function play(){
        echo 'Weather broadcast: sunny day.';
    }
    
    /**
     * 
     */
    public static function getBand(){
        return self::$BAND;
    }
    
}

?>
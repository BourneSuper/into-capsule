<?php

namespace BS\IC;

/**
 * @author BourneSuper
 *
 */
class ErrorMessage{
    
    public static $errorMsgArr = [
            'class name empty'              => 1000,
            'class header file not exists'        => 1001,
            'class cpp file not exists'        => 1002,
    ]; 
    
    /**
     * @param string $msg
     * @return number
     */
    public static function getErrorCodeByMsg( $msg ){
        if( !isset( self::$errorMsgArr[$msg] ) ){
            return 100;
        }
        return self::$errorMsgArr[$msg] ;
    }
    
}

?>
<?php

namespace BS\IC\tools;

/**
 * @author BourneSuper
 *
 */
class Helper{
    
    /**
     * @param string $className
     * @param string $methodName
     * @param array $arr
     * @return array
     */
    public static function echoIt( $methodName, $line, $arr, $logBool = true ){
        
        $str = $methodName . '(' . $line . ') ';
        
        foreach( $arr as $value ){
            if( is_string( $value ) ){
                $str .= $value . ' ';
            }else{
                $str .= json_encode( $value, JSON_UNESCAPED_UNICODE ) . ' ';
            }   
        }
        
        echo $str . PHP_EOL;
        
        /* if( $logBool ){
            \Log::info( $str . PHP_EOL );
        } */
        
    }
    
}
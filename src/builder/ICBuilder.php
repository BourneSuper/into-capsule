<?php

namespace BS\IC\builder;

/**
 * @author BourneSuper
 *
 */
interface ICBuilder extends IBuilder{
    

    /**
     * build c/cpp file for php extension
     * @param array $astArr, the array of param or ast
     * {@inheritDoc}
     * @see \BS\builder\IBuilder::build()
     */
    public function build( $astArr );
    
    
}

?>
<?php

namespace BS\IC\reader;

/**
 * @author BourneSuper
 *
 */
interface IReader{
    
    
    /**
     * return next (file) content.
     * @return string
     */
    public function next(  );
    
    /**
     * return current (file) content.
     * @return string
     */
    public function current(  );
    
    
    /**
     * get count of total files
     */
    public function getCount(  );    
    
    
}

?>
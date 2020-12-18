<?php

namespace BS\IC\reader\impl;

use BS\IC\reader\IReader;
use BS\IC\tools\Helper;

/**
 * traverse file path and read file content
 * @author BourneSuper
 */
class TraverseReader implements IReader{
    
    protected $fileArr;
    
    public function __construct( $rootPath ){
        
        $this->prepareFileArr( $rootPath );
        
        //var_dump(  $this->fileArr);
        
    }
    
    /**
     * @param string $path
     */
    protected function prepareFileArr( $path ){
        if( is_dir( $path ) ){
            if ( $dirHandler = opendir( $path ) ) {
                
                while ( ( $fileName = readdir($dirHandler ) ) !== false) {
                    
                    if( $fileName == '.' || $fileName == '..' ){
                        continue;    
                    }
                    
                    $fullFileName = $path . DIRECTORY_SEPARATOR . $fileName; 
                    
                    if( is_dir( $fullFileName ) ){
                        $this->prepareFileArr( $fullFileName );
                    }else{
                        $this->fileArr[ $fullFileName ] = $fullFileName;
                    }
                    
                }
                
                closedir($dirHandler);
            }
        } else {
            $this->fileArr[ $path ] = $path;
        }
        
    }
    
    
    /**
     * {@inheritDoc}
     * @see \BS\reader\i\Reader::next()
     */
    public function next(){
        
        $filePath = next( $this->fileArr );
        
        Helper::echoIt( __METHOD__, __LINE__, [ 'file name: ', $filePath ] );
        
        if( $filePath == false ){
            return false;
        }
        
        return file_get_contents( $filePath );
        
    }
    
    /**
     * {@inheritDoc}
     * @see \BS\reader\i\Reader::current()
     */
    public function current(){
        $filePath = current( $this->fileArr );
        
        Helper::echoIt( __METHOD__, __LINE__, [ 'file name: ', $filePath ] );
        
        if( $filePath == false ){
            return false;
        }
        
        return file_get_contents( $filePath );
    }
    
    /**
     * {@inheritDoc}
     * @see \BS\reader\IReader::getCount()
     */
    public function getCount(){
        return count( $this->fileArr );
        
    }


    
    
    
    
}

?>
<?php

namespace BS\IC;

use BS\IC\reader\impl\TraverseReader;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use BS\IC\tools\Helper;
use BS\IC\builder\ICBuilder;
use BS\IC\builder\impl\ClassBuilder;
/**
 * container and assembly for config and AST 
 * @author BourneSuper
 */
class WorkBench {
    
    public static $SRC_DIR_PATH = __DIR__ ;
    public static $TARGET_DIR_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'target' ;
    public static $DEFAULT_CONFIG_DIR_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
    public static $RESOURCE_DIR_PATH =  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'resource';
    
    const NODE_TYPE_BUILDER_MAP = [
            'Stmt_Class' => ClassBuilder::class,
    ];
    
    public static $configArr; 
    public static $TARGET_EXTENSION_DIR_PATH; 
    
    public $classASTArr;
    
    
    public function __construct( $extName ){
        self::$configArr = include self::$DEFAULT_CONFIG_DIR_PATH;
        
        self::$configArr = array_merge( 
                self::$configArr, 
                include '..' 
                    . DIRECTORY_SEPARATOR . 'config' 
                    . DIRECTORY_SEPARATOR . 'ext'
                    . DIRECTORY_SEPARATOR . $extName
                    . DIRECTORY_SEPARATOR . 'config.php' 
        );
        

        $this->generateTargetExtensionFolder();
        
    }
    
    public function generateTargetExtensionFolder( ){
        self::$TARGET_EXTENSION_DIR_PATH = self::$TARGET_DIR_PATH
                . DIRECTORY_SEPARATOR . 'ext'
                . DIRECTORY_SEPARATOR . self::$configArr[ 'ext_name' ];
            
        if( file_exists( self::$TARGET_EXTENSION_DIR_PATH ) ){
            rename( self::$TARGET_EXTENSION_DIR_PATH, self::$TARGET_EXTENSION_DIR_PATH . date('YmdHis') );
        }
                
        mkdir( self::$TARGET_EXTENSION_DIR_PATH );
    }
    
    /**
     * 
     */
    public function generate() {
        
        $phpFileFolder = self::$configArr[ 'php_file_path' ];
        
        $traverseReader = new TraverseReader( self::$RESOURCE_DIR_PATH );
        $count = $traverseReader->getCount();
        
        if( $count < 1 ){
            Helper::echoIt( __METHOD__, __LINE__, [ 'no files', $count ] );
            return ;
        }
        
        $parser = (new ParserFactory() )->create( ParserFactory::PREFER_PHP7 );
        $traverser = new NodeTraverser();
        
        try {
            for( $fileContentStr = $traverseReader->current(); $fileContentStr ; $fileContentStr = $traverseReader->next() ){
                
                $astArr = $parser->parse( $fileContentStr );
                
                $nodeType = $this->getPossibleNodeType( $astArr );
                
                $builderClassName = isset( WorkBench::NODE_TYPE_BUILDER_MAP[ $nodeType ] ) ? WorkBench::NODE_TYPE_BUILDER_MAP[ $nodeType ] : false ;
                
                Helper::echoIt( __METHOD__, __LINE__, [ 'nodeType:', $nodeType, ' builderClassName:', $builderClassName ] );
                
                
                if( $builderClassName != false ){
                    /**
                     * @var ICBuilder $builder
                     */
                    $builder = new $builderClassName();
                    $builder->build( $astArr );
                    
                }
                
                
                //         var_dump($ast);
                
                echo json_encode( $astArr, JSON_PRETTY_PRINT ) . PHP_EOL;
                
            }
        
        } catch ( \Exception $e ) {
            Helper::echoIt( __METHOD__, __LINE__, [ 'exception :', $e->getTraceAsString() ] );
            return;
        }
        
        
        $this->assemblyEntry();
        $this->assemblyMoudle();
        
    }
    
    /**
     * @param array $astArr
     * @return 
     */
    protected function getPossibleNodeType( $astArr ){
        $nodeTypeArr = array_keys( WorkBench::NODE_TYPE_BUILDER_MAP );
        
        $nodeType = false;
        
        $nodeType = $astArr[ 0 ]->getType();
        if( in_array( $nodeType, $nodeTypeArr ) ){
            return $nodeType;
        }
        
        if( isset( $astArr[ 0 ]->stmts ) ){
            foreach( $astArr[ 0 ]->stmts as $value ){ //echo json_encode( $value, JSON_PRETTY_PRINT ) . PHP_EOL;
                
                $nodeType = $value->getType();
                if( in_array( $nodeType, $nodeTypeArr )  ){
                    return $nodeType;
                }
            }
             
            return $astArr[ 0 ]->stmts[ 0 ]->getType();
        }
        
        return $nodeType;
    }
    
    /**
     * 
     */
    public function assemblyEntry(){
        
    }
    
    /**
     * 
     */
    public function assemblyMoudle(){
        
    }
    
    
    
    
}

require_once 'vendor/autoload.php';
( new WorkBench( 'into_capsule' ) )->generate();

return ;

?>
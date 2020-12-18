<?php

namespace BS\IC\builder\impl;

use BS\IC\builder\ICBuilder;
use BS\IC\WorkBench;
use PhpParser\Node\Stmt\ClassMethod;
use BS\IC\ErrorMessage;

/**
 * @author BourneSuper
 *
 * {
 *     "nodeType": "Stmt_ClassMethod",
 *     "flags": 1,
 *     "byRef": false,
 *     "name": {
 *         "nodeType": "Identifier",
 *         "name": "play",
 *         "attributes": {
 *             "startLine": 16,
 *             "endLine": 16
 *         }
 *     },
 *     "params": [],
 *     "returnType": null,
 *     "stmts": [
 *         {
 *             "nodeType": "Stmt_Echo",
 *             "exprs": [
 *                 {
 *                     "nodeType": "Scalar_String",
 *                     "value": "Weather broadcast: sunny day.",
 *                     "attributes": {
 *                         "startLine": 17,
 *                         "endLine": 17,
 *                         "kind": 1
 *                     }
 *                 }
 *             ],
 *             "attributes": {
 *                 "startLine": 17,
 *                 "endLine": 17
 *             }
 *         }
 *     ],
 *     "attrGroups": [],
 *     "attributes": {
 *         "startLine": 16,
 *         "comments": [
 *             {
 *                 "nodeType": "Comment_Doc",
 *                 "text": "\/**\n     * \n     *\/",
 *                 "line": 13,
 *                 "filePos": 168,
 *                 "tokenPos": 40,
 *                 "endLine": 15,
 *                 "endFilePos": 186,
 *                 "endTokenPos": 40
 *             }
 *         ],
 *         "endLine": 18
 *     }
 * }
 *
 * @author BourneSuper
 */
class MethodBuilder implements ICBuilder{
    
    public static $VISIBILITY_MODIFIER_ARR = [
            'isPublic'        => 'ZEND_ACC_PUBLIC',
            'isProtected'     => 'ZEND_ACC_PROTECTED',
            'isPrivate'       => 'ZEND_ACC_PRIVATE',
            'isAbstract'      => 'ZEND_ACC_ABSTRACT',
            'isFinal'         => 'ZEND_ACC_FINAL',
            'isStatic'        => 'ZEND_ACC_STATIC',
    ];
    
    
    /**
     * @var ClassBuilder
     */
    public $classBuilder;
    
    /**
     * @var array
     */
    public $methodNameArr;
    
    /**
     * @var ClassMethod[] 
     */
    public $methodArr;
    
    
    public function __construct( $classBuilder ){
        $this->classBuilder = $classBuilder;
    }

    /**
     * @param 
     * 
     * {@inheritDoc}
     * @see \BS\builder\ICBuilder::build()
     */
    public function build( $classNode ){
        
        $this->findMethodArr( $classNode );
        
        $this->buildHeaderFile( );
        
        $this->buildCppFile( );
        
    }
    
    /**
     * @param Class_ $classNode
     */
    public function findMethodArr( $classNode ){
        $this->methodArr = [];
        
        foreach( $classNode->stmts as $value ){
            if( $value->getType() == ( new ClassMethod( null ) )->getType() ){
                /**
                 * @var ClassMethod $value
                 */
                $this->methodArr[ $value->name->name ] = $value;
            }
        }
        
        return $this->methodArr;
        
    }

    /**
     * @throws \Exception
     */
    public function buildHeaderFile( ){
        if( ! file_exists( WorkBench::$TARGET_EXTENSION_DIR_PATH . DIRECTORY_SEPARATOR . $this->classBuilder->headerfileName ) ){
            throw new \Exception( 'class header file not exists', ErrorMessage::getErrorCodeByMsg('header file not exists') );
        }
        
        $rowArr = [];
        $rowTemplateStr = include WorkBench::$SRC_DIR_PATH
                . DIRECTORY_SEPARATOR . 'template'
                . DIRECTORY_SEPARATOR . 'methodHeaderFileTemplate.php' ;
        
        foreach( $this->methodArr as $key => $value ){
            $tempMethodName = $key;
            
            $tempRowStr = sprintf( $rowTemplateStr, $this->classBuilder->className, $tempMethodName );
            $rowArr[] = $tempRowStr;
        }
        
        $contentStr = implode( PHP_EOL, $rowArr ) . PHP_EOL;
        
        //
        file_put_contents(
                WorkBench::$TARGET_EXTENSION_DIR_PATH .
                DIRECTORY_SEPARATOR . $this->classBuilder->headerfileName,
                $contentStr, FILE_APPEND
        );
    
    
    
    
    }

    /**
     * @throws \Exception
     */
    public function buildCppFile( ){
        if( ! file_exists( WorkBench::$TARGET_EXTENSION_DIR_PATH . DIRECTORY_SEPARATOR . $this->classBuilder->cppfileName ) ){
            throw new \Exception( 'class cpp file not exists', ErrorMessage::getErrorCodeByMsg('class cpp file not exists') );
        }
        
        $this->buildMethod();
        
        $this->buildMethodEntry();
    
    }
    
    public function buildMethod( ){
        
        foreach( $this->methodArr as $key => $value ){
            /**
             * @var ClassMethod $value
             */
            $this->buildMethodParamDeclaration( $value );
            $this->buildMethodDetail( $value );
            
        }
        
        
    }
    
    /**
     * @param ClassMethod $value
     */
    public function buildMethodParamDeclaration( $value ){
        //TODO
    }
    
    /**
     * @param ClassMethod $value
     */
    public function buildMethodDetail( $value ){
        //TODO
    }
    
    
    /**
     * 
     */
    public function buildMethodEntry( ){
        //
        $rowArr = [];
        $templatePart3Str = include WorkBench::$SRC_DIR_PATH
                . DIRECTORY_SEPARATOR . 'template'
                . DIRECTORY_SEPARATOR . 'classCppFileTemplatePart3.php' ;
        
        foreach( $this->methodArr as $key => $value ){
            $tempMethodName = $key;
            
            $tempRowStr = sprintf( 
                    $templatePart3Str, 
                    $this->classBuilder->className, $tempMethodName, 
                    $this->classBuilder->className, $tempMethodName,
                    implode( ' | ', $this->extractMethodVisibilityModifierArr( $value ) )
            );
            $rowArr[] = $tempRowStr; 
        }
        
        
        //
        $templatePart2Str = include WorkBench::$SRC_DIR_PATH
                    . DIRECTORY_SEPARATOR . 'template'
                    . DIRECTORY_SEPARATOR . 'classCppFileTemplatePart2.php' ;
        
        
        $contentStr = sprintf( $templatePart2Str, 
                $this->classBuilder->getFullClassName(), 
                implode( PHP_EOL, $rowArr )  
        ) . PHP_EOL;
        
        //
        file_put_contents(
                WorkBench::$TARGET_EXTENSION_DIR_PATH .
                DIRECTORY_SEPARATOR . $this->classBuilder->cppfileName,
                $contentStr, FILE_APPEND
        );
        
    }
    
    /**
     * 
     * @param ClassMethod $classMethod
     * @return array $visibilityModifierSymbolArr
     */
    public function extractMethodVisibilityModifierArr( $classMethod ){
        $visibilityModifierSymbolArr = [];
        
        foreach( self::$VISIBILITY_MODIFIER_ARR as $key => $value ){
            if( $classMethod->$key() ){
                $visibilityModifierSymbolArr[] = $value;
            }
        }
        
        return $visibilityModifierSymbolArr;
        
    }
    
    
    
}

?>       
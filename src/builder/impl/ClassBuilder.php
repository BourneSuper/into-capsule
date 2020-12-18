<?php

namespace BS\IC\builder\impl;

use BS\IC\builder\ICBuilder;
use BS\IC\WorkBench;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\Class_;
use BS\IC\ErrorMessage;


/**
 * @author BourneSuper
 *
 */
class ClassBuilder implements ICBuilder{

    /**
     * @var string 
     */
    public $cppfileName;
    
    /**
     * @var string 
     */
    public $headerfileName; 
    
    /**
     * @var array 
     */
    public $namesapcePartArr;
    
    /**
     * @var string
     */
    public $useDeclarationArr;
    
    /**
     * @var string $className
     */
    public $className;
    //method
    
    
    /**
     * @param array $astArr
     * {@inheritDoc}
     * @see \BS\builder\ICBuilder::build()
     */
    public function build( $astArr ){
        
        $namespaceNode = $this->findNamespaceNode( $astArr );
        $this->extractNamespacePart( $namespaceNode );
        
        $useUseNodeArr = $this->findUseUseNodeArr( $astArr );
        $this->extractUseDeclaration( $useUseNodeArr );
        
        $classNode = $this->findClassNode( $astArr );
        $this->extractClassName( $classNode );
        
        //
        $this->generateFileName();
        
        $this->buildHeaderFile();
        $this->buildCppFile();
        
        //
        $propertyBuilder = new PropertyBuilder();
        $propertyBuilder->build( $classNode );
        
        $methodBuilder = new MethodBuilder( $this );
        $methodBuilder->build( $classNode );
        
        
        //var_dump($this);
    }
    
    /**
     * @param array $astArr
     * @return Namespace_|NULL
     */
    public function findNamespaceNode( $astArr ){
        foreach( $astArr as $value ){
            if( $value->getType() == ( new Namespace_() )->getType() ){
                return $value;
            }
        }
        
        return null;
        
    }
    
    /**
     * 
     * @param Namespace_ $namespaceNode
     * @return []
     */
    public function extractNamespacePart( $namespaceNode ){
        if( empty( $namespaceNode ) ){
            $this->namesapcePartArr = [ WorkBench::$configArr[ 'ext_name' ] ];
        }
        
        $this->namesapcePartArr = $namespaceNode->name->parts;
    }
    
    /**
     * @param array $astArr
     * @return array $useDeclarationNodeArr
     */
    public function findUseUseNodeArr( $astArr ){
        $useDeclarationNodeArr = [];
        
        foreach( $astArr[0]->stmts as $value ){
            if( $value->getType() == ( new Use_( [] ) )->getType() ){
                $useDeclarationNodeArr = array_merge( $useDeclarationNodeArr, $value->uses );
            }
        }
        
        return $useDeclarationNodeArr;
        
        
    }
    
    /**
     * @param UseUse[] $useUseNodeArr
     */
    public function extractUseDeclaration( $useUseNodeArr ){
        $this->useDeclarationArr = [];
        
        $tempStr = '';
        foreach( $useUseNodeArr as $value ){
            $tempStr = 'use ' . implode( '\\', $value->name->parts )  . '; ' ;
            $this->useDeclarationArr[] = $tempStr;
        }
        
        
    }
    
    /**
     * @param array $astArr
     * @return Class_|NULL
     */
    public function findClassNode( $astArr ){
        
        foreach( $astArr[0]->stmts as $value ){
            if( $value->getType() == ( new Class_( null ) )->getType() ){
                return $value;
            }
        }
        
        return null;
        
    }
    
    /**
     * @param Class_ $classNode
     */
    public function extractClassName( $classNode ){
        if( empty( $classNode ) ){
            throw new \Exception( 'class name empty', ErrorMessage::getErrorCodeByMsg( 'class name empty' ) );
        }
        
        $this->className = $classNode->name->name;
    }
 
    
    /**
     * 
     */
    public function buildHeaderFile( ){
        $contentStr = include WorkBench::$SRC_DIR_PATH 
                . DIRECTORY_SEPARATOR . 'template' 
                . DIRECTORY_SEPARATOR . 'classHeaderFileTemplate.php' ;
        
        $lsExtensionName = WorkBench::$configArr[ 'ext_name' ];
        $csExtensionName = strtoupper( $lsExtensionName );
        
        //
        $contentStr = sprintf( 
                $contentStr, 
                $lsExtensionName, $csExtensionName, $csExtensionName, $lsExtensionName,
                $lsExtensionName, $lsExtensionName, $csExtensionName, $csExtensionName,
                $csExtensionName
        );
        
        //
        file_put_contents( 
                WorkBench::$TARGET_EXTENSION_DIR_PATH . 
                DIRECTORY_SEPARATOR . $this->headerfileName, 
                $contentStr, FILE_APPEND
        );
        
        
        
        
    }
    
    /**
     * 
     */
    public function buildCppFile( ){
        $contentStr = include WorkBench::$SRC_DIR_PATH 
                . DIRECTORY_SEPARATOR . 'template' 
                . DIRECTORY_SEPARATOR . 'classCppFileTemplatePart1.php' ;
        
        //
        $contentStr = sprintf( 
                $contentStr, 
                $this->headerfileName, $this->getFullClassName()
        );
        
        //
        file_put_contents( 
                WorkBench::$TARGET_EXTENSION_DIR_PATH . 
                DIRECTORY_SEPARATOR . $this->cppfileName, 
                $contentStr, FILE_APPEND
        );
        
        
        
        
    }
    
    /**
     * @return string[]
     */
    public function generateFileName(){
        
        $fullClassName = $this->getFullClassName();
        
        $this->cppfileName = $fullClassName . '.cpp';
        $this->headerfileName = 'php_' . $fullClassName . '.h';
        
        return [ $this->cppfileName, $this->headerfileName ];
        
    }
    
    /**
     * @return string
     */
    public function getFullClassName(){
        
        $namespaceSnakeCase = implode( '_', $this->namesapcePartArr );
        
        $fullClassName = $namespaceSnakeCase . '_' . $this->className;
        
        return $fullClassName;
    }

    
    
    
}

?>
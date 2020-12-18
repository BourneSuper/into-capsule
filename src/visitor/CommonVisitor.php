<?php

namespace BS\IC\visitor;

use PhpParser\NodeVisitor;
use BS\IC\tools\Helper;

/**
 * @author BourneSuper
 *
 */
class CommonVisitor implements NodeVisitor {
    
    
    /**
     * {@inheritDoc}
     * @see \PhpParser\NodeVisitor::beforeTraverse()
     */
    public function beforeTraverse( array $nodes ){
//         Helper::echoIt( __METHOD__, __LINE__, [ 'beforeTraverse', $nodes ] );
        
    }

    /**
     * {@inheritDoc}
     * @see \PhpParser\NodeVisitor::enterNode()
     */
    public function enterNode( \PhpParser\Node $node ){
//         Helper::echoIt( __METHOD__, __LINE__, [ 'enterNode', $node ] );
        
    }

    /**
     * {@inheritDoc}
     * @see \PhpParser\NodeVisitor::leaveNode()
     */
    public function leaveNode( \PhpParser\Node $node ){
//         Helper::echoIt( __METHOD__, __LINE__, [ 'leaveNode', $node ] );
        
        
        
    }

    /**
     * {@inheritDoc}
     * @see \PhpParser\NodeVisitor::afterTraverse()
     */
    public function afterTraverse( array $nodes ){
//         Helper::echoIt( __METHOD__, __LINE__, [ 'afterTraverse', $nodes ] );
        
    }

    
    
    
}

?>
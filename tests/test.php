<?php
require_once 'vendor/autoload.php';


use PhpParser\ParserFactory;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use BS\IC\reader\impl\TraverseReader;
use BS\IC\visitor\CommonVisitor;
use BS\IC\tools\Helper;



$traverseReader = new TraverseReader( __DIR__ . "/../src/resource" );
$count = $traverseReader->getCount();

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$traverser = new NodeTraverser();

if( $count < 1 ){
	Helper::echoIt( __METHOD__, __LINE__, [ 'no files', $count ] );
	return ;
}

$fileContentStr = $traverseReader->current();
do{
	try {
		$ast = $parser->parse( $fileContentStr );
		
// 		var_dump($ast);
		
		echo json_encode( $ast, JSON_PRETTY_PRINT ) . PHP_EOL;
		
// 		$traverser->addVisitor( new CommonVisitor() );
// 		$traverser->traverse( $ast );
		
		
// 		$dumper = new NodeDumper();
// 		echo $dumper->dump($ast) . PHP_EOL;
		
	} catch (Error $error) {
		Helper::echoIt( __METHOD__, __LINE__, [ 'Parse error:', $error->getTraceAsString() ] );
		return;
	}
	
}while ( $fileContentStr = $traverseReader->next() )





?>
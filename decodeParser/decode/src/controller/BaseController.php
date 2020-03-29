<?php 
class BaseController extends Controller
{
    public $layout = "layout.html";
    function init()
    {
        ini_set('session.save_handler', 'user');
        $v2 = new MySessionHandler();
        $v3 = [$v2, \true];
        session_set_save_handler($v2, \true);
        $v5 = [];
        session_start();
        header('Content-type: text/html; charset=utf-8');
    }
    function tips($arg0, $arg1)
    {
        $arg1 = "{'location.href=\"'}{$arg1}{'\";'}";
        echo "{'<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"'}{$arg0}{'\");'}{$arg1}{'}</script></head><body onload=\"sptips()\"></body></html>'}";
        exit;
    }
    function jump($arg1, $arg2 = 0)
    {
        echo "{'<html><head><meta http-equiv=\\'refresh\\' content=\\''}{$arg2}{';url='}{$arg1}{'\\'></head><body></body></html>'}";
        exit;
    }
    public static function err404($arg3, $arg4, $arg5, $arg0)
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
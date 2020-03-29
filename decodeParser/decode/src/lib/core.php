<?php 
set_error_handler('_err_handle');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$v6 = [$GLOBALS, require APP_DIR . DS . 'config.php'];
$GLOBALS = array_merge($GLOBALS, require APP_DIR . DS . 'config.php');
if ($GLOBALS['debug']) {
    $v8 = [-1];
    error_reporting(-1);
    ini_set('display_errors', 'On');
} else {
    $v12 = [E_ALL & ~(E_STRICT | E_NOTICE)];
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
}
if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https' || !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || !empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
    $GLOBALS['http_scheme'] = 'https://';
} else {
    $GLOBALS['http_scheme'] = 'http://';
}
if (!empty($GLOBALS['rewrite'])) {
    foreach ($GLOBALS['rewrite'] as $v18 => $v19) {
        if ('/' == $v18) {
            $v18 = '/$';
        }
        $v20 = [$v18, $GLOBALS['http_scheme']];
        if (0 !== stripos($v18, $GLOBALS['http_scheme'])) {
            $v22 = [$_SERVER['SCRIPT_NAME']];
            $v24 = [dirname($_SERVER['SCRIPT_NAME']), '/\\'];
            $v18 = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/' . $v18;
        }
        $v26 = [array('\\\\', $GLOBALS['http_scheme'], '/', '<', '>', '.'), array('', '', '\\/', '(?P<', '>[-\\w]+)', '\\.'), $v18];
        $v18 = '/' . str_ireplace(array('\\\\', $GLOBALS['http_scheme'], '/', '<', '>', '.'), array('', '', '\\/', '(?P<', '>[-\\w]+)', '\\.'), $v18) . '/i';
        if (preg_match($v18, $_SERVER['REQUEST_URI'], $v28)) {
            $v31 = explode('/', $v19);
            if (isset($v31[2])) {
                list($_GET['m'], $_GET['c'], $_GET['a']) = $v31;
            } else {
                list($_GET['c'], $_GET['a']) = $v31;
            }
            foreach ($v28 as $v32 => $v33) {
                $v34 = [$v32];
                if (!is_int($v32)) {
                    $_GET[$v32] = $v33;
                }
            }
            break;
        }
    }
}
$v36 = [$_GET['m']];
$v38 = isset($_GET['m']) ? strtolower($_GET['m']) : '';
$v39 = [$_GET['c']];
$v41 = isset($_GET['c']) ? strtolower($_GET['c']) : 'main';
$v42 = [$_GET['a']];
$v44 = isset($_GET['a']) ? strtolower($_GET['a']) : 'index';
$v45 = [$_GET['s']];
$v47 = isset($_GET['s']) ? strtolower($_GET['s']) : 'custom';
spl_autoload_register('inner_autoload');
function inner_autoload($arg0)
{
    global $v38, $v47;
    $arg0 = str_replace('\\', '/', $arg0);
    $v54 = explode('/', $arg0);
    $arg0 = end($v54);
    foreach (array('model', 'include', 'controller' . (empty($v38) ? '' : DS . $v38), $v47) as $v55) {
        $v56 = APP_DIR . DS . $v55 . DS . $arg0 . '.php';
        $v57 = [$v56];
        if (file_exists($v56)) {
            include $v56;
            return;
        }
    }
}
escape($_REQUEST);
escape($_POST);
escape($_GET);
$v59 = $v41 . 'Controller';
$v60 = 'action' . $v44;
if (!empty($v38)) {
    $v61 = [$v38];
    if (!is_available_classname($v38)) {
        $v63 = ["{'Err: Module \\''}{$v38}{'\\' is not correct!'}"];
        _err_router("{'Err: Module \\''}{$v38}{'\\' is not correct!'}");
    }
    $v65 = [APP_DIR . DS . 'controller' . DS . $v38];
    if (!is_dir(APP_DIR . DS . 'controller' . DS . $v38)) {
        $v67 = ["{'Err: Module \\''}{$v38}{'\\' is not exists!'}"];
        _err_router("{'Err: Module \\''}{$v38}{'\\' is not exists!'}");
    }
}
$v69 = [$v41];
if (!is_available_classname($v41)) {
    $v71 = ["{'Err: Controller \\''}{$v59}{'\\' is not correct!'}"];
    _err_router("{'Err: Controller \\''}{$v59}{'\\' is not correct!'}");
}
$v73 = [$v59, \true];
if (!class_exists($v59, \true)) {
    $v75 = ["{'Err: Controller \\''}{$v59}{'\\' is not exists!'}"];
    _err_router("{'Err: Controller \\''}{$v59}{'\\' is not exists!'}");
}
$v77 = [$v59, $v60];
if (!method_exists($v59, $v60)) {
    $v79 = ["{'Err: Method \\''}{$v60}{'\\' of \\''}{$v59}{'\\' is not exists!'}"];
    _err_router("{'Err: Method \\''}{$v60}{'\\' of \\''}{$v59}{'\\' is not exists!'}");
}
$v81 = new $v59();
$v81->{$v60}();
if ($v81->{'_auto_display'}) {
    $v82 = (empty($v38) ? '' : $v38 . DS) . $v41 . '_' . $v44 . '.html';
    $v83 = [APP_DIR . DS . 'view' . DS . $v82];
    if (file_exists(APP_DIR . DS . 'view' . DS . $v82)) {
        $v81->{'display'}($v82);
    }
}
function url($arg1 = 'main', $arg2 = 'index', $arg3 = array())
{
    $v85 = [$arg1];
    if (is_array($arg1)) {
        $arg3 = $arg1;
        $arg1 = $arg3['c'];
        unset($arg3['c']);
        $arg2 = $arg3['a'];
        unset($arg3['a']);
    }
    $v87 = [$arg3];
    $v89 = empty($arg3) ? '' : '&' . http_build_query($arg3);
    $v90 = [$arg1, '/'];
    if (strpos($arg1, '/') !== \false) {
        list($v94, $arg1) = explode('/', $arg1);
        $v31 = "{$v94}{'/'}{$arg1}{'/'}{$arg2}";
        $v95 = $_SERVER['SCRIPT_NAME'] . "{'?m='}{$v94}{'&c='}{$arg1}{'&a='}{$arg2}{$v89}";
    } else {
        $v94 = '';
        $v31 = "{$arg1}{'/'}{$arg2}";
        $v95 = $_SERVER['SCRIPT_NAME'] . "{'?c='}{$arg1}{'&a='}{$arg2}{$v89}";
    }
    if (!empty($GLOBALS['rewrite'])) {
        if (!isset($GLOBALS['url_array_instances'][$v95])) {
            foreach ($GLOBALS['rewrite'] as $v18 => $v19) {
                $v96 = [array('/', '<a>', '<c>', '<m>'), array('\\/', '(?P<a>\\w+)', '(?P<c>\\w+)', '(?P<m>\\w+)'), $v19];
                $v19 = '/^' . str_ireplace(array('/', '<a>', '<c>', '<m>'), array('\\/', '(?P<a>\\w+)', '(?P<c>\\w+)', '(?P<m>\\w+)'), $v19) . '/i';
                if (preg_match($v19, $v31, $v28)) {
                    $v98 = [array('<a>', '<c>', '<m>'), array($arg2, $arg1, $v94), $v18];
                    $v18 = str_ireplace(array('<a>', '<c>', '<m>'), array($arg2, $arg1, $v94), $v18);
                    $v100 = 0;
                    $v101 = [$v18, '<'];
                    $v103 = substr_count($v18, '<');
                    if (!empty($arg3) && $v103 > 0) {
                        foreach ($arg3 as $v104 => $v105) {
                            $v106 = [$v18, '<' . $v104 . '>'];
                            if (\false !== stripos($v18, '<' . $v104 . '>')) {
                                $v100++;
                            }
                        }
                    }
                    if ($v103 == $v100) {
                        $GLOBALS['url_array_instances'][$v95] = $v18;
                        if (!empty($arg3)) {
                            $v108 = array();
                            foreach ($arg3 as $v109 => $v110) {
                                $v111 = 0;
                                $v112 = ['<' . $v109 . '>', $v110, $GLOBALS['url_array_instances'][$v95], $v111];
                                $GLOBALS['url_array_instances'][$v95] = str_ireplace('<' . $v109 . '>', $v110, $GLOBALS['url_array_instances'][$v95], $v111);
                                if (!$v111) {
                                    $v108[$v109] = $v110;
                                }
                            }
                            $v114 = [$v108];
                            $GLOBALS['url_array_instances'][$v95] = preg_replace('/<\\w+>/', '', $GLOBALS['url_array_instances'][$v95]) . (!empty($v108) ? '?' . http_build_query($v108) : '');
                        }
                        $v116 = [$GLOBALS['url_array_instances'][$v95], $GLOBALS['http_scheme']];
                        if (0 !== stripos($GLOBALS['url_array_instances'][$v95], $GLOBALS['http_scheme'])) {
                            $v118 = [$_SERVER['SCRIPT_NAME']];
                            $v120 = [dirname($_SERVER['SCRIPT_NAME']), '/\\'];
                            $GLOBALS['url_array_instances'][$v95] = $GLOBALS['http_scheme'] . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/' . $GLOBALS['url_array_instances'][$v95];
                        }
                        return $GLOBALS['url_array_instances'][$v95];
                    }
                }
            }
            return isset($GLOBALS['url_array_instances'][$v95]) ? $GLOBALS['url_array_instances'][$v95] : $v95;
        }
        return $GLOBALS['url_array_instances'][$v95];
    }
    return $v95;
}
function dump($arg4, $arg5 = false)
{
    $v122 = [$arg4, \true];
    $v124 = print_r($arg4, \true);
    if (!$GLOBALS['debug']) {
        $v127 = [str_replace('
', '', $v124)];
        return error_log(str_replace('
', '', $v124));
    }
    $v129 = [$v124];
    echo '<html><head><meta http-equiv=\\\\\"Content-Type\\\\\" content=\\\\\"text/html; charset=utf-8\\\\\"></head><body><div align=left><pre>' . htmlspecialchars($v124) . '</pre></div></body></html>';
    if ($arg5) {
        exit;
    }
}
function is_available_classname($arg6)
{
    return preg_match('/[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*/', $arg6);
}
function escape(&$v110)
{
    $v131 = [$v110];
    if (is_array($v110)) {
        foreach ($v110 as &$v133) {
            escape($v133);
        }
    } else {
        $v134 = [['\'', '(', ')', '\\'], ['‘', '（', '）', ''], $v110];
        $v110 = str_replace(['\'', '(', ')', '\\'], ['‘', '（', '）', ''], $v110);
    }
}
function arg($arg6, $arg7 = null, $arg8 = false)
{
    if (isset($_SERVER[$arg6])) {
        $v110 = $_SERVER[$arg6];
    } else {
        if (isset($_REQUEST[$arg6])) {
            $v110 = $_REQUEST[$arg6];
        } else {
            $v110 = $arg7;
        }
    }
    if ($arg8) {
        $v136 = [$v110];
        $v110 = trim($v110);
    }
    return $v110;
}
function _err_router($arg9)
{
    global $v38, $v41, $v44;
    if (!method_exists('BaseController', 'err404')) {
        $v140 = [$arg9];
        err($arg9);
    } else {
        BaseController::err404($v38, $v41, $v44, $arg9);
    }
}
function _err_handle($arg10, $arg11, $arg12, $arg13)
{
    $v142 = [];
    $v143 = [];
    if (0 === error_reporting() || 30711 === error_reporting()) {
        return \false;
    }
    $v146 = 'ERROR';
    if ($arg10 == E_WARNING) {
        $v146 = 'WARNING';
    }
    if ($arg10 == E_NOTICE) {
        $v146 = 'NOTICE';
    }
    if ($arg10 == E_STRICT) {
        $v146 = 'STRICT';
    }
    if ($arg10 == 8192) {
        $v146 = 'DEPRECATED';
    }
    $v147 = ["{$v146}{': '}{$arg11}{' in '}{$arg12}{' on line '}{$arg13}", $v146];
    err("{$v146}{': '}{$arg11}{' in '}{$arg12}{' on line '}{$arg13}", $v146);
}
function err($arg9, $v146 = 'NOTICE')
{
    global $v149;
    if (!$GLOBALS['debug']) {
        return;
    }
    if (!isset($v149)) {
        $v149 = new Logger();
    }
    $v149->{'add'}($arg9, $v146);
}
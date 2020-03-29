<?php 
date_default_timezone_set('PRC');
$v2 = array(
    'rewrite' => array(
        'admin/index.html' => 'admin/main/index',
        'admin/<c>_<a>.html' => 'admin/<c>/<a>',
        '<m>/<c>/<a>' => '<m>/<c>/<a>',
        '<c>/<a>' => '<c>/<a>',
        '/' => 'main/index'
    )
);
$v5 = array(
    'hardphp' => array(
        'debug' => 0,
        'mysql' => array(
            'MYSQL_HOST' => gethostbyname('mysql'),
            'MYSQL_PORT' => '3306',
            'MYSQL_USER' => 'db',
            'MYSQL_DB' => 'db',
            'MYSQL_PASS' => 'db',
            'MYSQL_CHARSET' => 'utf8'
        )
    ),
    'speedphp.com' => array(
        'debug' => 0,
        'mysql' => array()
    )
);
if (empty($v5['hardphp'])) {
    die('配置域名不正确，请确认hardphp的配置是否存在！');
}
return $v5['hardphp'] + $v2;
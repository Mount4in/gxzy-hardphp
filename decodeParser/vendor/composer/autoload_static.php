<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteb2af5c2813e586deaf1bac5d8657272
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpParser\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/php-parser/lib/PhpParser',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteb2af5c2813e586deaf1bac5d8657272::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteb2af5c2813e586deaf1bac5d8657272::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
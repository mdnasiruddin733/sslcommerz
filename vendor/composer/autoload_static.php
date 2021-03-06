<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit67d591fa0ef95f458d08f7638e81f8dd
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Webpane\\Sslcommerz\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Webpane\\Sslcommerz\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit67d591fa0ef95f458d08f7638e81f8dd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit67d591fa0ef95f458d08f7638e81f8dd::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit67d591fa0ef95f458d08f7638e81f8dd::$classMap;

        }, null, ClassLoader::class);
    }
}

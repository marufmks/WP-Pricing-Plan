<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit653c81be2bcd975776aefbb2fd055058
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pricing_Plan\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pricing_Plan\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit653c81be2bcd975776aefbb2fd055058::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit653c81be2bcd975776aefbb2fd055058::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit653c81be2bcd975776aefbb2fd055058::$classMap;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34c2d7bff8ec5722ceac9f7971d98146
{
    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Dropbox' => 
            array (
                0 => __DIR__ . '/..' . '/dropbox/dropbox-sdk/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit34c2d7bff8ec5722ceac9f7971d98146::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}

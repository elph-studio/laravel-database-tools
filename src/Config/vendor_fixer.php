<?php

/**
 * This is plain PHP script src/Helper/Plain/VendorFixer.php config, and it is not supposed to be used in Laravel app.
 * VendorFixer script updates vendors code on `composer install` and helps us to maintain our apps without rewriting
 * massive vendors code blocks and classes.
 *
 * All below configs are used in all apps that runs Core lib.
 * To override or update this config in specific apps, create `config/vendor_fixer.php` file in your app and add this:

$config = require '/app/vendor/elph-studio/laravel-helpers/src/Config/vendor_fixer.php';

$config['replace_content']['file/path'] = [
    [
        'from' => '...',
        'to' => '...',
    ],
];

return $config;

 */

declare(strict_types=1);

return [
    'replace_content' => [
        'vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php' => [
            [
                'from' => 'use Closure;',
                'to' => "use Closure;\nuse Elph\LaravelDatabase\Migration\Blueprint;",
            ],
        ],
    ],
];

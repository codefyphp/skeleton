<?php

declare(strict_types=1);

use Codefy\Framework\Application;

use function Codefy\Framework\Helpers\resource_path;

return [
    /*
    |--------------------------------------------------------------------------
    | Template paths.
    |--------------------------------------------------------------------------
    | Path of templates. If using Fenom, it must be a string. If using Native
    | it must be an array or if using Foil, you can use a string or array
    | for path.
    */
    'path' => ['framework' => resource_path(path: 'views')],

    /*
    |--------------------------------------------------------------------------
    | Cached templates.
    |--------------------------------------------------------------------------
    | Only if supported by the view being used.
    */

    'cache' => resource_path(path: 'views'.Application::DS.'cache'),

    /*
    |--------------------------------------------------------------------------
    | View Options.
    |--------------------------------------------------------------------------
    | Only if supported by the view being used.
    */

    'options' => [] ,
];

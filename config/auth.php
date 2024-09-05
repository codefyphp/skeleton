<?php

use function Codefy\Framework\Helpers\env;

return [
    'login_url' => env('APP_BASE_URL') . '/admin/login/',

    'admin_url' => env('APP_BASE_URL') . '/admin/',
];

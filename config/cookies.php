<?php

declare(strict_types=1);

return [
    'path' => '/',
    'domain' => 'codefy.ddev.site',
    'lifetime' => (int) 86400,
    'remember' => (int) 604800,
    'secure' => false,
    'samesite' => 'lax',
    'crypt' => 'sha256',
    'secret_key' => 'please change this to a secure salt',
];

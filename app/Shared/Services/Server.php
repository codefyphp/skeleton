<?php

declare (strict_types = 1);

namespace App\Shared\Services;

use function Codefy\Framework\Helpers\env;
use function is_string;
use function ltrim;
use function Qubus\Routing\Helpers\request;
use function Qubus\Security\Helpers\esc_url;
use function Qubus\Support\Helpers\add_trailing_slash;
use function Qubus\Support\Helpers\concat_ws;

class Server
{
    private function __construct()
    {
        // Prevent instantiation
    }

    public static function isSsl(): bool
    {
        if (isset($_SERVER['HTTPS'])
                && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')
        ) {
            return true;
        }

        if (isset($_SERVER['SERVER_PORT'])
                && (int)$_SERVER['SERVER_PORT'] === 443
        ) {
            return true;
        }

        // Handle reverse proxy / load balancer headers
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https'
        ) {
            return true;
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])
                && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on'
        ) {
            return true;
        }

        return false;
    }

    public static function siteUrl(string $path = ''): string
    {
        $scheme = (static::isSsl() ? 'https://' : 'http://');
        $host = request()->getHost() ?? env(key: 'APP_BASE_URL');

        $url = $scheme . $host;
        $url  = concat_ws(string1: $url, string2: $path, separator: '');

        if ($path && is_string(value: $path)) {
            $url .= ltrim(string: $path, characters: '/');
            $url .= add_trailing_slash($url);
        }

        return esc_url(url: $url, scheme: [$scheme]);
    }
}

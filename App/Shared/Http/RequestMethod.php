<?php

declare(strict_types=1);

namespace App\Shared\Http;

final class RequestMethod
{
    public const GET = 'GET';

    public const POST = 'POST';

    public const PUT = 'PUT';

    public const DELETE = 'DELETE';

    public const PATCH = 'PATCH';

    public const HEAD = 'HEAD';

    public const OPTIONS = 'OPTIONS';

    public const CONNECT = 'CONNECT';

    public const TRACE = 'TRACE';

    public const ANY = [
        self::GET,
        self::POST,
        self::PUT,
        self::DELETE,
        self::PATCH,
        self::HEAD,
        self::OPTIONS,
        self::CONNECT,
        self::TRACE,
    ];

    /**
     * Checks if a given http method is not a safe method.
     *
     * @param string $method
     * @return bool
     */
    public static function isUnsafe(string $method): bool
    {
        return self::isSafe($method) === false;
    }

    /**
     * Checks if a given http method is a safe method.
     *
     * @param string $method
     * @return bool
     */
    public static function isSafe(string $method): bool
    {
        return in_array(strtoupper($method), [self::GET, self::HEAD, self::OPTIONS, self::TRACE]);
    }
}
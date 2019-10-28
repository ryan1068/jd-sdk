<?php

namespace JDSDK\Support;

/**
 * Class Url.
 */
class Url
{
    /**
     * Get current url.
     *
     * @return string
     */
    public static function current()
    {
        if (defined('PHPUNIT_RUNNING')) {
            return 'http://localhost';
        }

        $protocol = 'http://';

        if (!empty($_SERVER['HTTPS'])
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            $protocol = 'https://';
        }

        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}

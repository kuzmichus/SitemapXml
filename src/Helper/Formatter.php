<?php
namespace SitemapXml\Helper;

/**
 * Class Formatter
 * @package SitemapXml\Helper
 */
class Formatter
{
    /**
     * @var bool
     */
    private static $enabled = false;

    /**
     *
     */
    public static function enable()
    {
        self::$enabled = true;
    }

    /**
     *
     */
    public static function disable()
    {
        self::$enabled = false;
    }

    /**
     * @return bool
     */
    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * @param $line
     * @param int $level
     * @return string
     */
    public static function format($line, $level = 1)
    {
        if (self::isEnabled()) {
            $line = str_repeat('    ', $level - 1) . $line . PHP_EOL;
        }
        return $line;
    }
}

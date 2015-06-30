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
    static private $enabled = false;

    /**
     *
     */
    static public function enable()
    {
        self::$enabled = true;
    }

    /**
     *
     */
    static public function disable()
    {
        self::$enabled = false;
    }

    /**
     * @return bool
     */
    static public function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * @param $line
     * @param int $level
     * @return string
     */
    static public function format($line, $level = 1)
    {
        if (self::isEnabled()) {
            $line = str_repeat('    ', $level - 1) . $line . PHP_EOL;
        }
        return $line;
    }
}

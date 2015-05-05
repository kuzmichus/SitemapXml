<?php
namespace SitemapXml\Helper;


class Formatter
{
    static private $enabled = false;

    static public function enable()
    {
        self::$enabled = true;
    }

    static public function disable()
    {
        self::$enabled = false;
    }

    static public function isEnabled()
    {
        return self::$enabled;
    }

    static public function format($line, $level = 1)
    {
        if (self::isEnabled()) {
            $line = str_repeat('    ', $level - 1) . $line . PHP_EOL;
        }
        return $line;
    }
}

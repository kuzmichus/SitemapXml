<?php


namespace SitemapXml\Validators;


use SitemapXml\Exception\ResourceMovedPermanently;
use SitemapXml\Exception\ResourceNotFound;

/**
 * Class Availability
 * @package SitemapXml\Validators
 */
class Availability extends Validator
{
    const STRICT_SOFT   = '0';
    const STRICT_MIDDLE = '1';
    const STRICT_HARD   = '2';

    /**
     * @var string
     */
    static private $strictness = self::STRICT_MIDDLE;

    /**
     * @param $strictness
     * @throws NotAllowedValue
     */
    static public function setStrictness($strictness)
    {
        if (!in_array($strictness, array(self::STRICT_SOFT, self::STRICT_MIDDLE, self::STRICT_HARD))) {
            throw new NotAllowedValue($strictness . ' not allowed value in Availability::setStrictness()');
        }
        self::$strictness = $strictness;
    }

    /**
     * @return string
     */
    static public function getStrictness()
    {
        return self::$strictness;
    }

    /**
     * @param $data
     * @return bool
     * @throws \SitemapXml\Exception\ResourceMovedPermanently
     * @throws \SitemapXml\Exception\ResourceNotFound
     */
    static public function Validation($data)
    {
        if (self::getStrictness() == self::STRICT_SOFT) {
            return true;
        }

        $ch = curl_init($data);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 404 && self::getStrictness() >= self::STRICT_MIDDLE) {
            throw new ResourceNotFound('Resource ' . $data . ' not found.');
        } elseif (self::getStrictness() == self::STRICT_HARD) {
            if($code == 301) {
                throw new ResourceMovedPermanently('Resource ' . $data . ' moved permanently.');
            } elseif ($code != 200) {
                throw new ResourceNotFound('Resource ' . $data . ' return ' . $code . ' code.');
            }
        }

        return true;
    }
} 

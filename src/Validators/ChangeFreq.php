<?php


namespace SitemapXml\Validators;

use SitemapXml\Exception\NotAllowedValue;

/**
 * Class ChangeFreq
 * @package SitemapXml\Validators
 */
class ChangeFreq extends Validator
{
    /**
     * @var array
     */
    private static $afllowedValue = array(
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never'
    );

    /**
     * @param $data
     * @throws \SitemapXml\Exception\NotAllowedValue
     */
    public static function Validation($data)
    {
        if (!is_string($data) || !in_array($data, self::$afllowedValue)) {
            throw new NotAllowedValue($data . ' not allowed value in <changefreq></changefreq>');
        }
    }
}

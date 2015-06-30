<?php
namespace SitemapXml\Validators;

use SitemapXml\Exception\NotAllowedValue;

/**
 * Class Rating
 * @package SitemapXml\Validators
 */
class Rating extends Validator
{
    /**
     * @param $data
     * @return bool
     * @throws \SitemapXml\Exception\NotAllowedValue
     */
    public static function Validation($data)
    {
        if (!preg_match('/^([0-4]\.\d|5\.0)$/', $data)) {
            throw new NotAllowedValue('Not Allowed Value ' . $data . ' in rating');
        }

        return true;
    }
}

<?php
namespace SitemapXml\Validators;


use SitemapXml\Exception\NotAllowedValue;

/**
 * Class Priority
 * @package SitemapXml\Validators
 */
class Priority extends Validator
{

    /**
     * @param $data
     * @return bool
     * @throws \SitemapXml\Exception\NotAllowedValue
     */
    public static function Validation($data)
    {
        if (!preg_match('/^(0\.\d|1\.0)$/', $data)) {
            throw new NotAllowedValue('Not Allowed Value ' . $data . ' in priority');
        }
        return true;
    }
}

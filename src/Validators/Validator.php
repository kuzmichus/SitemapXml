<?php
namespace SitemapXml\Validators;

/**
 * Class Validator
 * @package SitemapXml\Validators
 */
abstract class Validator
{
    /**
     * @param $data
     * @return mixed
     */
    abstract public static function Validation($data);
}

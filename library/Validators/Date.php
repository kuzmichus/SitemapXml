<?php


namespace SitemapXml\Validators;


use SitemapXml\Exception\NotAllowedValue;

/**
 * Class Date
 * @package SitemapXml\Validators
 */
class Date extends Validator
{

    /**
     * @param $date
     * @return bool
     * @throws \SitemapXml\Exception\NotAllowedValue
     */
    public static function Validation($date)
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $date);
        $f1 = $d && $d->format($format) == $date;

        $format = 'Y-m-d\TH:i:sP';
        $d = \DateTime::createFromFormat($format, $date);
        $f2 = $d && $d->format($format) == $date;

        if (!$f1 && !$f2) {
            throw new NotAllowedValue('Not Allowed Value ' . $date . ' in date.');
        }
        return true;
    }
}

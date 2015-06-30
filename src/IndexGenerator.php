<?php
namespace SitemapXml;

use SitemapXml\Helper\Formatter;
use SitemapXml\Validators\Availability;

/**
 * Class IndexGenerator
 * @package SitemapXml
 */
class IndexGenerator extends Generator
{
    /**
     * @param $url
     * @param array $params
     * @return array
     */
    protected function buildRecord($url, $params = array())
    {
        $lines = array();
        Availability::Validation($url);

        $lines[] = Formatter::format('<sitemap>', 2);
        $lines[] = Formatter::format('<loc>' . $url . '</loc>', 3);

        $lines[] = Formatter::format('<lastmod>' . date('c') . '</lastmod>', 3);

        $lines[] = Formatter::format('</sitemap>', 2);
        return $lines;
    }

    /**
     * @return string
     */
    protected function beginFile()
    {
        return parent::beginFile() .
            Formatter::format('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
    }

    /**
     * @return string
     */
    protected function endFile()
    {
        return Formatter::format('</sitemapindex>') .
        parent::endFile();
    }
}

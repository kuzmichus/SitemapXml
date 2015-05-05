<?php


namespace SitemapXml;


use SitemapXml\Helper\Formatter;

/**
 * Class XmlYandexFeed
 * @package SitemapXml
 */
class XmlYandexFeed extends XmlGenerator
{
    /**
     * @param $url
     * @param array $params
     * @return array
     */
    protected function buildRecord($url, $params = array())
    {
        $feedLines = array();
        $lines = parent::buildRecord($url, $params);

        if (isset($params['ovs:video'])) {
            $this->addXmlns('ovs', 'http://video.yandex.ru/schemas/video_import');

            $feedLines[] = Formatter::format('<ovs:video>', 3);
            if (isset($params['ovs:video']['ovs:feed'])) {
                $feedLines[]= Formatter::format('<ovs:feed>' . $params['ovs:video']['ovs:feed'] . '</ovs:feed>', 4);
            }
            $feedLines[] = Formatter::format('</ovs:video>', 3);
        }

        array_splice($lines, count($lines) - 1, 0, $feedLines);

        return $lines;
    }

}

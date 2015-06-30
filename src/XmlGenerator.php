<?php


namespace SitemapXml;

use SitemapXml\Helper\Formatter;
use SitemapXml\Validators\ChangeFreq;
use SitemapXml\Validators\Date;
use SitemapXml\Validators\Priority;

/**
 * Class XmlGenerator
 * @package SitemapXml
 */
class XmlGenerator extends Generator
{
    /**
     * @var null
     */
    private $defaultChangefreq  = null;
    /**
     * @var null
     */
    private $defaultPriority    = null;

    /**
     * @var array
     */
    private $xmlns = array();

    /**
     * @param string $namespace
     * @param string $url
     * @return $this
     */
    public function addXmlns($namespace, $url)
    {
        $this->xmlns[$namespace] = $url;
        return $this;
    }

    /**
     * @return string
     */
    protected function beginFile()
    {
        $xmlns = '';
        foreach ($this->xmlns as $n => $u) {
            $xmlns .= ' xmlns:' . $n . '="' . $u . '"';
        }
        return parent::beginFile() .
            Formatter::format('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . $xmlns . '>');
    }

    /**
     * @return string
     */
    protected function endFile()
    {
        return Formatter::format('</urlset>') .
            parent::endFile();
    }

    /**
     * @param $url
     * @param array $params
     * @return string[]
     */
    protected function buildRecord($url, $params = array())
    {
        $lines = array();

        $lines[] = Formatter::format('<url>', 2);
        $lines[] = Formatter::format('<loc>' . $url . '</loc>', 3);

        if (isset($params['lastmod'])) {
            Date::Validation($params['lastmod']);
            $lines[] = Formatter::format('<lastmod>' . $params['lastmod'] . '</lastmod>', 3);
        } else {
            $lines[] = Formatter::format('<lastmod>' . date('c') . '</lastmod>', 3);
        }

        if (isset($params['changefreq'])) {
            ChangeFreq::Validation($params['changefreq']);
            $lines[] = Formatter::format('<changefreq>' . $params['changefreq'] . '</changefreq>', 3);
        } elseif ($this->defaultChangefreq != null) {
            $lines[] = Formatter::format('<changefreq>' . $this->defaultChangefreq . '</changefreq>', 3);
        }

        if (isset($params['priority'])) {
            Priority::Validation($params['priority']);
            $lines[] = Formatter::format('<priority>' . $params['priority'] . '</priority>', 3);
        } elseif ($this->getDefaultPriority() != null) {
            $lines[] = Formatter::format('<priority>' . $this->getDefaultPriority() . '</priority>', 3);
        }

        $lines[] = Formatter::format('</url>', 2);
        return $lines;
    }

    /**
     * @param null $changefreq
     * @return $this
     */
    public function setDefaultChangefreq($changefreq = null)
    {
        ChangeFreq::Validation($changefreq);
        $this->defaultChangefreq = $changefreq;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefaultChangefreq()
    {
        return $this->defaultChangefreq;
    }

    /**
     * @param string $priority
     * @return $this
     */
    public function setDefaultPriority($priority = '1.0')
    {
        Priority::Validation($priority);
        $this->defaultPriority = $priority;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefaultPriority()
    {
        return $this->defaultPriority;
    }
}

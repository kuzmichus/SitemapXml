<?php

namespace SitemapXml;
use SitemapXml\Validators\Availability;

/**
 * Class Generator
 */
abstract class Generator
{
    const ALWAYS    = 'always';
    const HOURLY    = 'hourly';
    const DAILY     = 'daily';
    const WEEKLY    = 'weekly';
    const MONTHLY   = 'monthly';
    const YEARLY    = 'yearly';
    const NEVER     = 'never';

    const SPLIT_NONE        = 0;
    const SPLIT_BY_NUMBER   = 1;
    const SPLIT_BY_SIZE     = 2;

    /**
     * @var string
     */
    private $destinationDir = '';

    /**
     * @var string
     */
    private $filePattern = 'sitemap%02d.xml';

    /**
     * @var string
     */
    private $indexFilePattern   = 'sitemap.xml';
    /**
     * @var int
     */
    private $indexFile = 0;
    /**
     * @var int
     */
    private $splitRecords = 1000;

    /**
     * @var int
     */
    private $maxSizeFile = 10485760; // 10 Mb

    /**
     * @var bool
     */
    private $autoSplit = true;
    /**
     * @var bool
     */
    private $multyMap = false;

    /**
     * @var IndexGenerator
     */
    private $indexGenerator = null;

    /**
     * @var null
     */
    private $handle = null;
    /**
     * @var int
     */
    private $sizeFile = 0;
    /**
     * @var int
     */
    private $countLine = 0;
    /**
     * @var string
     */
    private $tempFile = '';

    /**
     * @var string
     */
    private $localPath = '';
    /**
     * @var string
     */
    private $publicPath = '';

    /**
     * @var null
     */
    private $localIndexPath = null;
    /**
     * @var null
     */
    private $publicIndexPath = null;

    /**
     * @param string $localIndexFile
     * @return Generator
     */
    public function setLocalIndexPath($localIndexFile)
    {
        $this->localIndexPath = rtrim($localIndexFile, DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalIndexPath()
    {
        if ($this->localIndexPath == null) {
            $this->localIndexPath = $this->getLocalPath();
        }
        return $this->localIndexPath;
    }

    /**
     * @param string $localPath
     * @return Generator
     */
    public function setLocalPath($localPath)
    {
        $this->localPath = rtrim($localPath, DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    /**
     * @param string $publicIndexFile
     */
    public function setPublicIndexPath($publicIndexFile)
    {
        $this->publicIndexPath = rtrim($publicIndexFile, DIRECTORY_SEPARATOR);
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicIndexPath()
    {
        return $this->publicIndexPath;
    }

    /**
     * @param string $publicPath
     */
    public function setPublicPath($publicPath)
    {
        $this->publicPath = $publicPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return $this->publicPath;
    }

    /**
     * @param boolean $autoSplit
     */
    public function setAutoSplit($autoSplit)
    {
        $this->autoSplit = $autoSplit;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAutoSplit()
    {
        return $this->autoSplit;
    }

    /**
     * @param string $filePattern
     */
    public function setFilePattern($filePattern)
    {
        $this->filePattern = $filePattern;
        $this->indexFile = 0;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePattern()
    {
        return $this->filePattern;
    }

    /**
     * @param string $indexFilePattern
     */
    public function setIndexFilePattern($indexFilePattern)
    {
        $this->indexFilePattern = $indexFilePattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndexFilePattern()
    {
        return $this->indexFilePattern;
    }

    /**
     * @param string $destinationDir
     */
    public function setDestinationDir($destinationDir)
    {
        $this->destinationDir = $destinationDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationDir()
    {
        return $this->destinationDir;
    }

    /**
     *
     */
    public function start()
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'sitemap');
        $this->countLine = 0;
        $this->sizeFile = 0;

        $this->handle = fopen($this->tempFile, 'w');
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        if ($this->multyMap) {
            return $this->getLocalPath() . DIRECTORY_SEPARATOR . sprintf($this->filePattern, $this->indexFile);
        } else {
            return $this->getLocalIndexPath() . DIRECTORY_SEPARATOR . $this->indexFilePattern;
        }
    }

    /**
     * @return string
     */
    private function getRemoteFileName()
    {
        if ($this->multyMap) {
            return $this->getPublicPath() . '/' . sprintf($this->filePattern, $this->indexFile);
        } else {
            return $this->getPublicIndexPath() . '/' . $this->indexFilePattern;
        }
    }

    /**
     *
     */
    public function finish()
    {

        fclose($this->handle);

        $fileName = $this->getFileName();

        if ($this->multyMap) {
            $f = fopen($fileName, 'w');
            $this->getIndexGenerator()->add($this->getRemoteFileName());
            $this->getIndexGenerator()->finish();
        } else {
            $f = fopen($fileName, 'w');
        }
        fwrite($f, $this->beginFile());
        fwrite($f, file_get_contents($this->tempFile));
        unlink($this->tempFile);

        $this->tempFile = '';
        fwrite($f, $this->endFile());
        fclose($f);
    }

    /**
     * @return $this
     */
    public function flush()
    {
        $this->multyMap = true;

        $fileName = $this->getFileName();
        fclose($this->handle);

        $f = fopen($fileName, 'w');
        fwrite($f, $this->beginFile());
        fwrite($f, file_get_contents($this->tempFile));
        unlink($this->tempFile);

        $this->tempFile = '';
        fwrite($f, $this->endFile());
        fclose($f);

        $this->getIndexGenerator()->add($this->getRemoteFileName());
        $this->indexFile++;

        $this->tempFile = tempnam(sys_get_temp_dir(), 'sitemap');
        $this->countLine = 0;
        $this->sizeFile = 0;
        $this->handle = fopen($this->tempFile, 'w');

        return $this;
    }

    /**
     * @return IndexGenerator
     */
    private function getIndexGenerator()
    {
        if ($this->indexGenerator == null) {
            $this->indexGenerator = new IndexGenerator();
            $this->indexGenerator->setAutoSplit(self::SPLIT_NONE)
                ->setLocalPath($this->getLocalIndexPath())
                ->setIndexFilePattern($this->getIndexFilePattern())
                ->start();
        }
        return $this->indexGenerator;
    }

    /**
     * @param $url
     * @param array $params
     */
    public function add($url, $params = array())
    {
        Availability::Validation($url);
        $content = implode('', $this->buildRecord($url, $params));

        if ($this->autoSplit == self::SPLIT_BY_NUMBER && $this->countLine >= $this->splitRecords) {
            $this->flush();
        } elseif($this->autoSplit == self::SPLIT_BY_SIZE && $this->sizeFile + strlen($content) >= (1024 * 1024 * 10 - strlen($this->beginFile()) - strlen($this->endFile()))) {
            $this->flush();
        }


        fwrite($this->handle, $content);
        $this->sizeFile += strlen($content);
        $this->countLine++;
    }

    /**
     * @param $url
     * @param array $params
     * @return array
     */
    abstract protected function buildRecord($url, $params = array());

    /**
     * @return string
     */
    protected function beginFile()
    {
        return '<?xml version="1.0" encoding="UTF-8" ?>' .PHP_EOL;
    }

    /**
     * @return string
     */
    protected function endFile()
    {
        return PHP_EOL;
    }


}

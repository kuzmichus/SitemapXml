[![Code Climate](https://codeclimate.com/github/kuzmichus/SitemapXml/badges/gpa.svg)](https://codeclimate.com/github/kuzmichus/SitemapXml) [![Build Status](https://scrutinizer-ci.com/g/kuzmichus/SitemapXml/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kuzmichus/SitemapXml/build-status/master)


# SitemapXml
Generation Sitemap XML file


Install the latest version with

```bash
$ composer require kuzmichus/sitemap-xml
```


Usage
-----

```php
<?php

use SitemapXml\Generator;

require __DIR__ . '/../vendor/autoload.php';


\SitemapXml\Helper\Formatter::enable();
\SitemapXml\Validators\Availability::setStrictness(\SitemapXml\Validators\Availability::STRICT_SOFT);

$io = new \Symfony\Component\Console\Output\ConsoleOutput();

$generator = new \SitemapXml\XmlGoogleGenerator();
$generator
    ->setIo($io)
    ->setLocalPath(__DIR__ . '/public/')
    ->setPublicIndexPath('http://example.com/')
    ->setDefaultChangefreq(Generator::DAILY)
    ->setDefaultPriority('0.8')
    ->start();

    try {

        $generator->add('http://example.com/');
        $generator->add('http://example.com/page1/');
        $generator->add('http://example.com/page2/');
        $generator->add('http://example.com/page3/');

    } catch (\SitemapXml\Exception\ResourceNotFound $e) {
        echo $e->getMessage() . PHP_EOL;
    }

$generator->finish();

```

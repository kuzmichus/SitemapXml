<?php

use SitemapXml\Generator;

require __DIR__ . '/../vendor/autoload.php';


\SitemapXml\Helper\Formatter::enable();

$io = new \Symfony\Component\Console\Output\ConsoleOutput();
\SitemapXml\Validators\Availability::setStrictness(\SitemapXml\Validators\Availability::STRICT_SOFT);


$generator = new \SitemapXml\XmlGenerator();
$generator
    ->setIo($io)
    ->setLocalPath(__DIR__ . '/../public/')
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
    $io->writeln('<error>' . $e->getMessage() . '</error>');
}

$generator->finish();

<?php

/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 *
 * @author     rafal.wartalski@gmail.com
 */

namespace Humbug\Test\Config;

use Humbug\Config\JsonParser;

class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JsonParser
     */
    private $parser;

    protected function setUp()
    {
        $this->parser = new JsonParser();
    }

    public function testParseShouldReturnConfig()
    {
        $config = $this->parser->parseFile(__DIR__ . '/../_files/config/');
        $this->assertInstanceOf('stdClass', $config);
    }

    public function testParseShouldReturnDistConfigIfNoOther()
    {
        $config = $this->parser->parseFile(__DIR__ . '/../_files/config3/');
        $this->assertInstanceOf('stdClass', $config);
    }

    public function testParsesNonDistFilePreferentially()
    {
        $this->setExpectedException(
            '\Humbug\Exception\JsonConfigException'
        );
        $config = $this->parser->parseFile(__DIR__ . '/../_files/config4/');
    }

    public function testShouldRiseExceptionWhenFileNotExists()
    {
        $this->setExpectedException(
            '\Humbug\Exception\JsonConfigException',
            'Please create a humbug.json(.dist) file.'
        );
        $this->parser->parseFile('it/not/exists/');
    }

    public function testShouldRiseExceptionWhenFileContainsInvalidJson()
    {
        $this->setExpectedExceptionRegExp(
            '\Humbug\Exception\JsonConfigException',
            '/Error parsing configuration file JSON.*/'
        );
        $this->parser->parseFile(__DIR__ . '/../_files/config2/');
    }
}

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

namespace Humbug\Test\Adapter\Phpunit\XmlConfiguration;

use Humbug\Adapter\Phpunit\XmlConfiguration\AcceleratorListener;

class AcceleratorListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AcceleratorListener
     */
    private $acceleratorListener;

    protected function setUp()
    {
        $this->acceleratorListener = new AcceleratorListener();
    }

    public function testShouldBeVisitor()
    {
        $this->assertInstanceOf('Humbug\Adapter\Phpunit\XmlConfiguration\Visitor', $this->acceleratorListener);
    }

    public function testShouldUpdateDomElement()
    {
        $dom = new \DOMDocument();
        $domElement = $dom->createElement('listener');
        $dom->appendChild($domElement);

        $this->acceleratorListener->visitElement($domElement);

        $this->assertEquals('\MyBuilder\PhpunitAccelerator\TestListener', $domElement->getAttribute('class'));

        $boolean = (new \DOMXPath($dom))->query('/listener/arguments/boolean');

        $this->assertEquals(1, $boolean->length);
        $this->assertEquals('true', $boolean->item(0)->nodeValue);
    }
}

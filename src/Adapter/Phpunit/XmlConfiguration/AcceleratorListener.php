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

namespace Humbug\Adapter\Phpunit\XmlConfiguration;

class AcceleratorListener implements Visitor
{
    public function visitElement(\DOMElement $domElement)
    {
        (new ObjectVisitor('\MyBuilder\PhpunitAccelerator\TestListener', [true]))->visitElement($domElement);
    }
}

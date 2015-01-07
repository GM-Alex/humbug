<?php
/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 */

namespace Humbug\Test\Mutator\Arithmetic;

use Humbug\Mutator;

class ModulusTest extends \PHPUnit_Framework_TestCase
{

    public function testReturnsTokenEquivalentToMultiplicationOperator()
    {
        $mutation = new Mutator\Arithmetic\Modulus;
        $this->assertEquals(
            [
                10 => '*'
            ],
            $mutation->getMutation([], 10)
        );
    }

    public function testMutatesModulusToMultiplication()
    {
        $tokens = [10 => '%'];

        $this->assertTrue(Mutator\Arithmetic\Modulus::mutates($tokens, 10));

        $tokens = [11 => '*'];

        $this->assertFalse(Mutator\Arithmetic\Modulus::mutates($tokens, 11));
    }

}

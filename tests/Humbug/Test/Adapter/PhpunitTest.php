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

namespace Humbug\Adapter;

use Mockery as m;

class PhpunitTest extends \PHPUnit_Framework_TestCase
{

    protected $bootstrap = null;

    public function setUp()
    {
        $this->root = dirname(__FILE__) . '/_files';
    }

    public function tearDown()
    {
        if (file_exists(sys_get_temp_dir() . '/humbug.xml')) {
            unlink(sys_get_temp_dir() . '/humbug.xml');
        }
        m::close();
    }

    /**
     * @group baserun
     */
    public function testAdapterRunsDefaultPhpunitCommand()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => __DIR__ . '/_files/phpunit',
            'getTestRunDirectory'      => __DIR__ . '/_files/phpunit',
            'getBaseDirectory'      => __DIR__ . '/_files/phpunit',
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'MM1_MathTest MathTest.php'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertStringStartsWith('TAP version', $result);
        $this->assertTrue($adapter->ok($result));
    }

    public function testAdapterRunsPhpunitCommandWithAlltestsFileTarget()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => __DIR__ . '/_files/phpunit2',
            'getTestRunDirectory'      => __DIR__ . '/_files/phpunit2',
            'getBaseDirectory'      => __DIR__ . '/_files/phpunit2',
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'AllTests.php'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertStringStartsWith('TAP version', $result);
        $this->assertTrue($adapter->ok($result));
    }

    public function testAdapterDetectsTestsPassing()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => $this->root,
            'getTestRunDirectory'      => $this->root,
            'getBaseDirectory'      => $this->root,
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'PassTest'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertTrue($adapter->ok($result));
    }

    public function testAdapterDetectsTestsFailingFromTestFail()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => $this->root,
            'getTestRunDirectory'      => $this->root,
            'getBaseDirectory'      => $this->root,
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'FailTest'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertFalse($adapter->ok($result));
    }

    public function testAdapterDetectsTestsFailingFromException()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => $this->root,
            'getTestRunDirectory'      => $this->root,
            'getBaseDirectory'      => $this->root,
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'ExceptionTest'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertFalse($adapter->ok($result));
    }

    public function testAdapterDetectsTestsFailingFromError()
    {
        $container = m::mock('\Humbug\Container');
        $container->shouldReceive([
            'getSourceList'    => $this->root,
            'getTestRunDirectory'      => $this->root,
            'getBaseDirectory'      => $this->root,
            'getTimeout'            => 1200,
            'getCacheDirectory'     => sys_get_temp_dir(),
            'getAdapterOptions'     => [],
            'getBootstrap'          => '',
            'getAdapterConstraints' => 'ErrorTest'
        ]);

        $adapter = new Phpunit;
        $process = $adapter->getProcess(
            $container,
            true,
            true
        );
        $process->run();

        $result = $process->getOutput();

        $this->assertFalse($adapter->ok($result));
    }

    public function testAdapterOutputProcessingDetectsFailOverMultipleLinesWithNoDepOnFinalStatusReport()
    {
        $adapter = new Phpunit;
        $output = <<<OUTPUT
TAP version 13
not ok 1 - Error: Humbug\Adapter\PhpunitTest::testAdapterRunsDefaultPhpunitCommand
ok 78 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testReturnsTokenEquivalentToLessThanOrEqualTo
ok 79 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo
ok 80 - Humbug\Test\Mutator\ConditionalBoundary\LessThanTest::testReturnsTokenEquivalentToLessThanOrEqualTo
ok 81 - Humbug\Test\Mutator\ConditionalBoundary\LessThanTest::testMutatesLessThanToLessThanOrEqualTo
not ok 103 - Error: Humbug\Test\Utility\TestTimeAnalyserTest::testAnalysisOfJunitLogFormatShowsLeastTimeTestCaseFirst
1..103

OUTPUT;
        $this->assertFalse($adapter->ok($output));
    }
}

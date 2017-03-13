<?php
/**
 * PHP 7 SDK for the KATANA(tm) Framework (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php7
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Tests\Api;

use Katana\Sdk\Api\Api;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

function foo() {}

class Test {
    public static function staticTest() {}
    public function instanceTest() {}
}

class ApiStub extends Api {}

class ApiTest extends TestCase
{
    /**
     * @var Api
     */
    private $api;

    private $logger;

    /**
     * @var Mapping
     */
    private $mapping;

    public function setUp()
    {
        $this->logger = $this->prophesize(KatanaLogger::class);
        $this->logger->getLevel()->willReturn(KatanaLogger::LOG_DEBUG);

        $this->mapping = $this->prophesize(Mapping::class);

        $this->api = new ApiStub(
            $this->logger->reveal(),
            $this->prophesize(Component::class)->reveal(),
            $this->mapping->reveal(),
            '/',
            'test',
            '1.0',
            '1.0.0',
            [],
            true
        );
    }

    public function testLogNull()
    {
        $this->logger->debug('NULL')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(null));
    }

    public function testLogBoolTrue()
    {
        $this->logger->debug('TRUE')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(true));
    }

    public function testLogBoolFalse()
    {
        $this->logger->debug('FALSE')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(false));
    }

    public function testLogPositiveInteger()
    {
        $this->logger->debug('15')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(15));
    }

    public function testLogNegativeInteger()
    {
        $this->logger->debug('-12')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(-12));
    }

    public function testLogPositiveFloat()
    {
        $this->logger->debug('555.45')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(555.45));
    }

    public function testLogPositiveTruncatedFloat()
    {
        $this->logger->debug('555.123456789')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(555.123456789123));
    }

    public function testLogNegativeFloat()
    {
        $this->logger->debug('-777.54')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(-777.54));
    }

    public function testLogNegativeTruncatedFloat()
    {
        $this->logger->debug('-777.123456789')->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(-777.123456789123));
    }

    public function testLogArrayList()
    {
        $this->logger->debug(json_encode(['a', 'b', 'c']))->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(['a', 'b', 'c']));
    }

    public function testLogArrayDict()
    {
        $this->logger->debug(json_encode(['a' => 'b', 'c' => 4]))->shouldBeCalled();
        $this->assertEquals(true, $this->api->log(['a' => 'b', 'c' => 4]));
    }

    public function testLogCallableClassArray()
    {
        $className = Test::class;
        $this->logger->debug("function $className::staticTest")->shouldBeCalled();
        $this->assertEquals(true, $this->api->log([Test::class, 'staticTest']));
    }

    public function testLogCallableObjectArray()
    {
        $className = Test::class;
        $obj = new Test();
        $this->logger->debug("function $className::instanceTest")->shouldBeCalled();
        $this->assertEquals(true, $this->api->log([$obj, 'instanceTest']));
    }

    public function testLogCallableClosure()
    {
        $closure = function () {};
        $this->logger->debug("function anonymous")->shouldBeCalled();
        $this->assertEquals(true, $this->api->log($closure));
    }

    public function testLogResource()
    {
        $resource = fopen(__FILE__, 'r');
        $this->logger->debug((string) $resource)->shouldBeCalled();
        $this->assertEquals(true, $this->api->log($resource));
    }

    public function testLogLimit()
    {
        $string = str_pad('Test string', 120000, '.');
        $expected = str_pad('Test string', 100000, '.');
        $this->logger->debug($expected)->shouldBeCalled();
        $this->assertEquals(true, $this->api->log($string));
    }

    public function testLogNotDebug()
    {
        $this->logger = $this->prophesize(KatanaLogger::class);

        $this->api = new ApiStub(
            $this->logger->reveal(),
            $this->prophesize(Component::class)->reveal(),
            $this->prophesize(Mapping::class)->reveal(),
            '/',
            'test',
            '1.0',
            '1.0.0',
            [],
            false
        );

        $this->logger->debug(Argument::any())->shouldNotBeCalled();
        $this->logger->getLevel()->willReturn(KatanaLogger::LOG_INFO);
        $this->assertEquals(false, $this->api->log('test'));
    }

    public function testGetServices()
    {
        $service1 = $this->prophesize(ServiceSchema::class);
        $service1->getName()->willReturn('foo');
        $service1->getVersion()->willReturn('1.0.0');
        $service2 = $this->prophesize(ServiceSchema::class);
        $service2->getName()->willReturn('bar');
        $service2->getVersion()->willReturn('1.1.2');

        $this->mapping->getAll()->willReturn([
            $service1,
            $service2,
        ]);

        $this->assertEquals([
            ['service' => 'foo', 'version' => '1.0.0'],
            ['service' => 'bar', 'version' => '1.1.2'],
        ], $this->api->getServices());
    }

    public function testGetEmptyServices()
    {
        $this->mapping->getAll()->willReturn([]);
        $this->assertEquals([], $this->api->getServices());
    }
}

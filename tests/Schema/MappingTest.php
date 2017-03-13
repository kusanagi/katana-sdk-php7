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

namespace Katana\Sdk\Tests\Schema;

use Katana\Sdk\Exception\SchemaException;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;
use PHPUnit\Framework\TestCase;

class MappingTest extends TestCase
{
    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @var array
     */
    private $services = [];

    public function setUp()
    {
        $service1 = $this->prophesize(ServiceSchema::class);
        $service1->getName()->willReturn('test');
        $service1->getVersion()->willReturn('1.0.0');
        $this->services[1] = $service1->reveal();
        $service2 = $this->prophesize(ServiceSchema::class);
        $service2->getName()->willReturn('test');
        $service2->getVersion()->willReturn('1.0.1');
        $this->services[2] = $service2->reveal();
        $service3 = $this->prophesize(ServiceSchema::class);
        $service3->getName()->willReturn('foo');
        $service3->getVersion()->willReturn('1.0.1');
        $this->services[3] = $service3->reveal();

        $this->mapping = new Mapping();
        $this->mapping->load($this->services);
    }

    public function testFindService()
    {
        $this->assertEquals($this->services[2], $this->mapping->find('test', '1.0.1'));
    }

    public function testFindNone()
    {
        $this->expectException(SchemaException::class);
        $this->expectExceptionMessage('Cannot resolve schema for Service: test (1.2.1)');
        $this->mapping->find('test', '1.2.1');
    }

    public function testResolveWildcard()
    {
        $this->assertEquals($this->services[2], $this->mapping->find('test', '1.0.*'));
    }

    public function testResolveWithMultipleWildcards()
    {
        $this->assertEquals($this->services[2], $this->mapping->find('test', '1.*.*'));
    }

    public function testGetAllServices()
    {
        $this->assertEquals([
            $this->services[1],
            $this->services[2],
            $this->services[3],
        ], $this->mapping->getAll());
    }
}

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

use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Transport\ForeignRelation;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;
use PHPUnit\Framework\TestCase;

class TransportTest extends TestCase
{
    /**
     * @var Transport
     */
    private $transport;

    public function setUp()
    {
        $this->transport = new Transport(
            new TransportMeta('', '', '', '', [], 0, ['localhost', 'localhost'], [], 0),
            new TransportFiles(),
            [],
            [],
            [],
            new TransportCalls(),
            new TransportTransactions(),
            new TransportErrors()
        );
    }

    public function testData()
    {
        $transport = $this->transport;
        $this->assertEquals([], $transport->getData());

        $transport->setData('service1', '1.1.1', 'foo', [
            'first' => 1,
            'second' => true,
        ]);
        $this->assertCount(1, $transport->getData());

        $serviceData = $transport->getData()[0];
        $this->assertEquals('service1', $serviceData->getName());
        $this->assertFalse($serviceData->getActions()[0]->isCollection());

        $transport->setData('service2', '1.1.1', 'foo', [[
            'first' => 1,
            'second' => true,
        ]]);
        $this->assertCount(2, $transport->getData());

        $transport->setData('service2', '1.1.1', 'foo', [
            'first' => 1,
            'second' => true,
        ]);
        $this->assertCount(2, $transport->getData());

        $serviceData = $transport->getData()[1];
        $this->assertCount(2, $serviceData->getActions());
        $this->assertTrue($serviceData->getActions()[0]->isCollection());
        $this->assertFalse($serviceData->getActions()[1]->isCollection());
    }

    public function testRelations()
    {
        $transport = $this->transport;
        $this->assertEquals([], $transport->getRelations());

        $transport->addSimpleRelation('serviceFrom', 'idFrom', 'serviceTo', 'idTo');
        $this->assertCount(1, $transport->getRelations());

        $transport->addMultipleRelation('serviceFrom', 'idFrom', 'serviceTo2', ['idTo1', 'idTo2']);
        $this->assertCount(1, $transport->getRelations());

        $transport->addSimpleRelation('serviceFrom2', 'idFrom2', 'serviceTo', 'idTo');
        $this->assertCount(2, $transport->getRelations());

        $relation = $transport->getRelations()[0];
        $this->assertCount(2, $relation->getForeignRelations());
        $this->assertContainsOnlyInstancesOf(ForeignRelation::class, $relation->getForeignRelations());

        $foreignRelation = $relation->getForeignRelations()[0];
        $this->assertEquals('serviceTo', $foreignRelation->getName());
        $this->assertEquals('one', $foreignRelation->getType());
        $this->assertEquals(['idTo'], $foreignRelation->getForeignKeys());

        $foreignRelation = $relation->getForeignRelations()[1];
        $this->assertEquals('serviceTo2', $foreignRelation->getName());
        $this->assertEquals('many', $foreignRelation->getType());
        $this->assertEquals(['idTo1', 'idTo2'], $foreignRelation->getForeignKeys());

        $relation = $transport->getRelations()[1];
        $this->assertCount(1, $relation->getForeignRelations());
        $this->assertContainsOnlyInstancesOf(ForeignRelation::class, $relation->getForeignRelations());
    }

    public function testLinks()
    {
        $transport = $this->transport;
        $this->assertEquals([], $transport->getLinks());

        $transport->setLink('name', 'self', 'http://example.com/1');
        $this->assertCount(1, $transport->getLinks());

        $link = $transport->getLinks()[0];
        $this->assertEquals($link->getName(), 'name');
        $this->assertEquals($link->getLink(), 'self');
        $this->assertEquals($link->getUri(), 'http://example.com/1');

        $transport->setLink('name', 'next', 'http://example.com/2');
        $this->assertCount(2, $transport->getLinks());

        $transport->setLink('name', 'self', 'http://example.com/3');
        $this->assertCount(2, $transport->getLinks());

        $link = $transport->getLinks()[0];
        $this->assertEquals($link->getLink(), 'self');
        $this->assertEquals($link->getUri(), 'http://example.com/3');
    }
}

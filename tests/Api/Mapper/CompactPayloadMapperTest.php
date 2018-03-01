<?php
/**
 * PHP 7 SDK for the KATANA(tm) Framework (http://katana.kusanagi.io)
 * Copyright (c) 2016-2018 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php7
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2018 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Tests\Api\Mapper;

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Transport\Caller;
use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Api\Transport\ServiceData;
use Katana\Sdk\Api\Transport\Transaction;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;
use PHPUnit\Framework\TestCase;

class CompactPayloadMapperTest extends TestCase
{
    public function testRequestMapping()
    {
        $command = json_decode(
            file_get_contents(__DIR__ . '/request.json'),
            true
        );

        $mapper = new CompactPayloadMapper();

        $httpRequest = $mapper->getHttpRequest($command);
        $this->assertEquals('Request body', $httpRequest->getBody());
        $this->assertEquals('http://localhost/users', $httpRequest->getUrl());
        $this->assertEquals('http', $httpRequest->getUrlScheme());
        $this->assertEquals('localhost', $httpRequest->getUrlHost());
        $this->assertEquals('/users', $httpRequest->getUrlPath());

        $call = $mapper->getServiceCall($command);
        $this->assertEquals('test-service', $call->getService());
        $this->assertEquals('test-action', $call->getAction());
        $this->assertEquals('1.0.0', $call->getVersion());

        $payloadMeta = $mapper->getPayloadMeta($command);
        $this->assertEquals('05cb5b6b-dbc2-4e27-86b0-ef1463199c78', $payloadMeta->getId());
        $this->assertEquals('2016-09-14T22:49:37.703915+00:00', $payloadMeta->getTimestamp());
        $this->assertEquals('http', $payloadMeta->getGatewayProtocol());
        $this->assertEquals('http://127.0.0.1:80', $payloadMeta->getGatewayAddress());
        $this->assertEquals('205.81.5.62:7681', $payloadMeta->getClientAddress());
    }

    public function testTransportMapping()
    {
        $command = json_decode(
            file_get_contents(__DIR__ . '/response.json'),
            true
        );

        $mapper = new CompactPayloadMapper();

        $transport = $mapper->getTransport($command);
        $this->assertInstanceOf(Transport::class, $transport);
        $this->assertInstanceOf(TransportMeta::class, $transport->getMeta());
        $this->assertInstanceOf(TransportFiles::class, $transport->getFiles());
        $this->assertContainsOnlyInstancesOf(ServiceData::class, $transport->getData());
        $this->assertContainsOnlyInstancesOf(Relation::class, $transport->getRelations());
        $this->assertContainsOnlyInstancesOf(Link::class, $transport->getLinks());
        $this->assertContainsOnlyInstancesOf(Caller::class, $transport->getCalls());
        $this->assertContainsOnlyInstancesOf(Transaction::class, $transport->getTransactions());
        $this->assertContainsOnlyInstancesOf(Transport\Error::class, $transport->getErrors());
        $this->assertInstanceOf(File::class, $transport->getBody());
    }
}

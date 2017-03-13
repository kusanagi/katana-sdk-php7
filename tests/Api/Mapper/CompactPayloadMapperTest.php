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

namespace Katana\Sdk\Tests\Api\Mapper;

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Api\Transport;
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

        $this->assertEquals('http', $mapper->getGatewayProtocol($command));
        $this->assertEquals('http://127.0.0.1:80', $mapper->getGatewayAddress($command));
        $this->assertEquals('205.81.5.62:7681', $mapper->getClientAddress($command));
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
        $this->assertInstanceOf(TransportData::class, $transport->getData());
        $this->assertInstanceOf(TransportRelations::class, $transport->getRelations());
        $this->assertInstanceOf(TransportLinks::class, $transport->getLinks());
        $this->assertInstanceOf(TransportCalls::class, $transport->getCalls());
        $this->assertInstanceOf(TransportTransactions::class, $transport->getTransactions());
        $this->assertInstanceOf(TransportErrors::class, $transport->getErrors());
        $this->assertInstanceOf(File::class, $transport->getBody());
    }
}

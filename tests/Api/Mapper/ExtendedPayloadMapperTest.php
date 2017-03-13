<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Tests\Api\Mapper;

use Katana\Sdk\Api\Mapper\ExtendedPayloadMapper;
use PHPUnit\Framework\TestCase;

class ExtendedPayloadMapperTest extends TestCase
{
    public function testRequestMapping()
    {
        $command = json_decode(
            file_get_contents(__DIR__ . '/extended_request.json'),
            true
        );

        $mapper = new ExtendedPayloadMapper();

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
}

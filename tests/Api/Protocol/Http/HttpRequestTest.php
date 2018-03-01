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

namespace Katana\Sdk\Tests\Api\Protocol\Http;

use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use PHPUnit\Framework\TestCase;

class HttpRequestTest extends TestCase
{
    public function testHttpMethod()
    {
        $httpRequest = new HttpRequest('', 'get', '');
        $this->assertFalse($httpRequest->isMethod('post'));
        $this->assertTrue($httpRequest->isMethod('get'));
        $this->assertEquals('get', $httpRequest->getMethod());
    }

    public function testEmptyUrl()
    {
        $httpRequest = new HttpRequest('', '', '');
        $this->assertEquals('', $httpRequest->getUrl());
        $this->assertEquals('', $httpRequest->getUrlScheme());
        $this->assertEquals('', $httpRequest->getUrlHost());
        $this->assertEquals('', $httpRequest->getUrlPath());
    }

    public function testUrlWithNoPath()
    {
        $httpRequest = new HttpRequest('', '', 'https://example.com');
        $this->assertEquals('https://example.com', $httpRequest->getUrl());
        $this->assertEquals('https', $httpRequest->getUrlScheme());
        $this->assertEquals('example.com', $httpRequest->getUrlHost());
        $this->assertEquals('', $httpRequest->getUrlPath());
    }

    public function testFullUrl()
    {
        $httpRequest = new HttpRequest('', '', 'https://example.com/path');
        $this->assertEquals('https://example.com/path', $httpRequest->getUrl());
        $this->assertEquals('https', $httpRequest->getUrlScheme());
        $this->assertEquals('example.com', $httpRequest->getUrlHost());
        $this->assertEquals('/path', $httpRequest->getUrlPath());
    }
}

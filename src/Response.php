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

namespace Katana\Sdk;

use Katana\Sdk\Api\ApiInterface;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\TransportReader;

interface Response extends ApiInterface
{
    /**
     * @return HttpRequest
     */
    public function getHttpRequest(): HttpRequest;

    /**
     * @return HttpResponse
     */
    public function getHttpResponse(): HttpResponse;

    /**
     * @return Transport
     */
    public function getTransport();

    /**
     * @return string
     */
    public function getGatewayProtocol(): string;

    /**
     * @return string
     */
    public function getGatewayAddress(): string;

    /**
     * Determine whether or not the initial **Service** has a return value.
     *
     * @return bool
     */
    public function hasReturn(): bool;

    /**
     * Return the value returned by the initial **Service**.
     *
     * @return mixed
     */
    public function getReturn();

    /**
     * Get a request attribute or $default if it does not exist.
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getRequestAttribute(string $name, string $default = ''): string;

    /**
     * Get request attributes.
     *
     * @return array
     */
    public function getRequestAttributes(): array;
}

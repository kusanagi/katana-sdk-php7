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
     * @return TransportReader
     */
    public function getTransport(): TransportReader;

    /**
     * @return string
     */
    public function getGatewayProtocol(): string;

    /**
     * @return string
     */
    public function getGatewayAddress(): string;
}

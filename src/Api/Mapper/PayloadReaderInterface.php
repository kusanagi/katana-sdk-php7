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

namespace Katana\Sdk\Api\Mapper;

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\ServiceCall;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;

/**
 * Interface for classes that build Api instances from command input
 *
 * @package Katana\Sdk\Api\Mapper
 */
interface PayloadReaderInterface
{
    /**
     * @param array $raw
     * @return Param[]
     */
    public function getParams(array $raw);

    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw);

    /**
     * @param array $raw
     * @return HttpRequest
     */
    public function getHttpRequest(array $raw);

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayProtocol(array $raw);

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayAddress(array $raw);

    /**
     * @param array $raw
     * @return string
     */
    public function getClientAddress(array $raw);

    /**
     * @param array $raw
     * @return HttpResponse
     */
    public function getHttpResponse(array $raw);

    /**
     * @param array $raw
     * @return ServiceCall
     */
    public function getServiceCall(array $raw);
}

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

namespace Katana\Sdk\Api\Mapper;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
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
 * Interface for classes that write command responses from Api instances
 *
 * @package Katana\Sdk\Api\Mapper
 */
interface PayloadWriterInterface
{
    /**
     * @param ActionApi $action
     * @return array
     */
    public function writeActionResponse(ActionApi $action);

    /**
     * @param RequestApi $request
     * @return array
     */
    public function writeRequestResponse(RequestApi $request);

    /**
     * @param ResponseApi $response
     * @return array
     */
    public function writeResponseResponse(ResponseApi $response);

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function writeErrorResponse($message = '', $code = 0, $status = '');

    /**
     * @param Transport $transport
     * @param array $output
     * @return array
     */
    public function writeTransport(Transport $transport, array $output);

    /**
     * @param ServiceCall $call
     * @param array $output
     * @return array
     */
    public function writeServiceCall(ServiceCall $call, array $output);

    /**
     * @param HttpResponse $response
     * @param array $output
     * @return array
     */
    public function writeHttpResponse(HttpResponse $response, array $output);
}

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

namespace Katana\Sdk\Messaging\Responder;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;

/**
 * Take an Api and respond with a json to stdout
 *
 * @package Katana\Sdk\Messaging\Responder
 */
class JsonResponder implements ResponderInterface
{
    /**
     * @param ResponseApi $response
     * @param PayloadWriterInterface $mapper
     */
    private function sendResponseResponse(ResponseApi $response, PayloadWriterInterface $mapper)
    {
        echo json_encode($mapper->writeResponseResponse($response));
    }

    /**
     * @param RequestApi $request
     * @param PayloadWriterInterface $mapper
     */
    private function sendRequestResponse(RequestApi $request, PayloadWriterInterface $mapper)
    {
        echo json_encode($mapper->writeRequestResponse($request));
    }

    /**
     * @param ActionApi $action
     * @param PayloadWriterInterface $mapper
     */
    private function sendActionResponse(ActionApi $action, PayloadWriterInterface $mapper)
    {
        echo json_encode($mapper->writeActionResponse($action));
    }

    /**
     * @param Api $api
     * @param PayloadWriterInterface $mapper
     */
    public function sendResponse(Api $api, PayloadWriterInterface $mapper)
    {
        if ($api instanceof ActionApi) {
            $this->sendActionResponse($api, $mapper);
        } elseif ($api instanceof ResponseApi) {
            $this->sendResponseResponse($api, $mapper);
        } elseif ($api instanceof RequestApi) {
            $this->sendRequestResponse($api, $mapper);
        }
    }

    /**
     * @param PayloadWriterInterface $mapper
     * @param string $message
     * @param int $code
     * @param string $status
     */
    public function sendErrorResponse(
        PayloadWriterInterface $mapper,
        $message = '',
        $code = 0,
        $status = ''
    ) {
        echo json_encode(
            $mapper->writeErrorResponse($message, $code, $status)
        );
    }

}

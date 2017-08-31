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
use Katana\Sdk\Exception\ResponderException;
use Katana\Sdk\Messaging\MessagePackSerializer;
use MessagePack\Exception\PackingFailedException;

/**
 * Take an Api and respond with a msgpack to ZeroMQ
 *
 * @package Katana\Sdk\Messaging\Responder
 */
class ZeroMqMultipartResponder implements ResponderInterface
{
    /**
     * @var mixed
     */
    private $socket;

    /**
     * @var MessagePackSerializer
     */
    private $serializer;

    /**
     * @param ResponseApi $response
     * @param PayloadWriterInterface $mapper
     */
    private function sendResponseResponse(ResponseApi $response, PayloadWriterInterface $mapper)
    {
        $message = $mapper->writeResponseResponse($response);
        $payload = $this->serializer->serialize($message);

        $this->socket->sendmulti(["\x00", $payload]);
    }

    /**
     * @param RequestApi $request
     * @param PayloadWriterInterface $mapper
     */
    private function sendRequestResponse(RequestApi $request, PayloadWriterInterface $mapper)
    {
        $message = $mapper->writeRequestResponse($request);
        $payload = $this->serializer->serialize($message);

        $this->socket->sendmulti(["\x00", $payload]);
    }

    /**
     * @param ActionApi $action
     * @param PayloadWriterInterface $mapper
     */
    private function sendActionResponse(ActionApi $action, PayloadWriterInterface $mapper)
    {
        $message = $mapper->writeActionResponse($action);
        $payload = $this->serializer->serialize($message);

        $controlString = '';
        $transportCalls = $action->getTransport()->getCalls()->getArray($action->getName());
        if (isset($transportCalls[$action->getVersion()])) {
            $controlString .= "\x01";
        }

        if ($action->getTransport()->hasFiles()) {
            $controlString .= "\x02";
        }

        if ($action->getTransport()->hasTransactions()) {
            $controlString .= "\x03";
        }

        if ($action->getTransport()->hasBody()) {
            $controlString .= "\x04";
        }

        if ($controlString === '') {
            $controlString = "\x00";
        }

        $this->socket->sendmulti([
            $controlString,
            $payload
        ]);
    }

    /**
     * ZeroMqMultipartActionResponder constructor.
     * @param MessagePackSerializer $serializer
     * @param $socket
     */
    public function __construct(MessagePackSerializer $serializer, $socket)
    {
        $this->serializer = $serializer;
        $this->socket = $socket;
    }

    /**
     * @param Api $api
     * @param PayloadWriterInterface $mapper
     */
    public function sendResponse(Api $api, PayloadWriterInterface $mapper)
    {
        try {
            if ($api instanceof ActionApi) {
                $this->sendActionResponse($api, $mapper);
            } elseif ($api instanceof ResponseApi) {
                $this->sendResponseResponse($api, $mapper);
            } elseif ($api instanceof RequestApi) {
                $this->sendRequestResponse($api, $mapper);
            }
        } catch (PackingFailedException $e) {
            throw new ResponderException('Could not pack the message', 1, $e);
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
        $payload = $this->serializer->serialize(
            $mapper->writeErrorResponse($message, $code, $status)
        );

        $this->socket->sendmulti(["\x00", $payload]);
    }
}

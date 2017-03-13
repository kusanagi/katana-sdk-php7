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

namespace Katana\Sdk\Messaging\RuntimeCaller;

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Exception\RuntimeCallException;
use Katana\Sdk\Mapper\CompactTransportMapper;
use Katana\Sdk\Messaging\MessagePackSerializer;
use ZMQ;
use ZMQPoll;
use ZMQSocket;

class ZeroMQRuntimeCaller
{
    /**
     * @var ZMQSocket
     */
    private $socket;

    /**
     * @var MessagePackSerializer
     */
    private $serializer;

    /**
     * @var CompactTransportMapper
     */
    private $mapper;

    /**
     * @param MessagePackSerializer $serializer
     * @param CompactTransportMapper $mapper
     * @param $socket
     */
    public function __construct(
        MessagePackSerializer $serializer,
        CompactTransportMapper $mapper,
        $socket
    ) {
        $this->serializer = $serializer;
        $this->mapper = $mapper;
        $this->socket = $socket;
    }

    /**
     * @param string $action
     * @param ActionTarget $target
     * @param Transport $transport
     * @param string $address
     * @param Param[] $params
     * @param File[] $files
     * @param int $timeout
     * @return mixed
     * @throws RuntimeCallException
     */
    public function call(
        $action,
        ActionTarget $target,
        Transport $transport,
        $address,
        array $params = [],
        array $files = [],
        $timeout = 1000
    ) {
        $message = [
            'c' => [
                'n' => 'runtime-call',
                'a' => [
                    'a' => $action,
                    'c' => [
                        $target->getService(),
                        $target->getVersion()->getVersion(),
                        $target->getAction()
                    ],
                    'T' => $this->mapper->writeTransport($transport),
                    'p' => $params,
                    'f' => $files,
                ],
            ],
            'm' => [
                's' => 'service',
            ],
        ];

        $payload = $this->serializer->serialize($message);

        $this->socket->connect($address);
        $this->socket->sendmulti([
            "\x01",
            $payload
        ], ZMQ::MODE_DONTWAIT);

        $read = $write = array();
        $poll = new ZMQPoll();
        $poll->add($this->socket, ZMQ::POLL_IN);
        $response = $poll->poll($read, $write, $timeout);

        if ($response > 0) {
            $reply = $this->socket->recv();
            $response = $this->serializer->unserialize($reply);

            if (isset($response['cr']['r']['E'])) {
                throw new RuntimeCallException("Error response: {$response['cr']['r']['E']['m']}");
            } else {
                echo json_encode($response), "\n";
                $return = $response['cr']['r']['R'];
                $this->mapper->merge($transport, $response['cr']['r']['T']);
            }

        } else {
            throw new RuntimeCallException("Runtime call timeout");
        }

        $this->socket->disconnect($address);

        return $return;
    }
}

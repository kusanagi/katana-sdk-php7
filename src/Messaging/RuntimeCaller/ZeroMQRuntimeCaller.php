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

namespace Katana\Sdk\Messaging\RuntimeCaller;

use ATimer\Timer;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\RuntimeCall;
use Katana\Sdk\Api\ServiceOrigin;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Exception\RuntimeCallException;
use Katana\Sdk\Mapper\CompactTransportMapper;
use Katana\Sdk\Mapper\RuntimeCallWriterInterface;
use Katana\Sdk\Mapper\TransportWriterInterface;
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
     * @var TransportWriterInterface
     */
    private $mapper;

    /**
     * @var RuntimeCallWriterInterface
     */
    private $runtimeCallWriter;

    /**
     * @var Timer
     */
    private $timer;

    /**
     * @param Param $param
     * @return array
     */
    private function writeParam(Param $param)
    {
        return [
            'n' => $param->getName(),
            'v' => $param->getValue(),
            't' => $param->getType(),
        ];
    }

    /**
     * @param MessagePackSerializer $serializer
     * @param TransportWriterInterface $mapper
     * @param $socket
     * @param RuntimeCallWriterInterface $runtimeCallWriter
     * @param Timer $timer
     */
    public function __construct(
        MessagePackSerializer $serializer,
        TransportWriterInterface $mapper,
        $socket,
        RuntimeCallWriterInterface $runtimeCallWriter,
        Timer $timer
    ) {
        $this->serializer = $serializer;
        $this->mapper = $mapper;
        $this->socket = $socket;
        $this->runtimeCallWriter = $runtimeCallWriter;
        $this->timer = $timer;
    }

    /**
     * @param string $service
     * @param string $version
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
        string $service,
        string $version,
        string $action,
        ActionTarget $target,
        Transport $transport,
        string $address,
        array $params = [],
        array $files = [],
        int $timeout = 10000
    ) {
        $message = $this->runtimeCallWriter->writeRuntimeCall(
            $action,
            $transport,
            $target,
            $params,
            $files
        );

        $payload = $this->serializer->serialize($message);

        $this->socket->connect($address);
        $this->socket->sendmulti([
            "\x01",
            $payload
        ], ZMQ::MODE_DONTWAIT);

        $read = $write = array();
        $poll = new ZMQPoll();
        $poll->add($this->socket, ZMQ::POLL_IN);

        $this->timer->start();
        $response = $poll->poll($read, $write, $timeout);
        $duration = $this->timer->stop() * 1000;

        if ($response > 0) {
            $reply = $this->socket->recv();
            $response = $this->serializer->unserialize($reply);

            if (isset($response['E'])) {
                throw new RuntimeCallException("Error response: {$response['E']['m']}");
            } else {
                $return = $response['cr']['r']['rv'];
                $this->mapper->merge($transport, $response['cr']['r']['T']);
                $transport->addCall(
                    new RuntimeCall(
                        new ServiceOrigin($service, $version),
                        'action',
                        $target->getService(),
                        $target->getVersion(),
                        $target->getAction(),
                        $duration,
                        $params,
                        $files
                    )
                );
            }

        } else {
            throw new RuntimeCallException("Runtime call timeout");
        }

        $this->socket->disconnect($address);

        return $return;
    }
}

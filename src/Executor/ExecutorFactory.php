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

namespace Katana\Sdk\Executor;

use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Api\Mapper\PayloadMapperInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\MessagePackSerializer;
use Katana\Sdk\Messaging\Responder\JsonResponder;
use Katana\Sdk\Messaging\Responder\ZeroMqMultipartResponder;
use Katana\Sdk\Schema\Mapping;
use MKraemer\ReactPCNTL\PCNTL;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use ZMQ;

/**
 * Builds an executor to process request
 *
 * @package Katana\Sdk\Console
 */
class ExecutorFactory
{
    /**
     * @var PayloadMapperInterface
     */
    protected $mapper;

    /**
     * @var KatanaLogger
     */
    protected $logger;

    /**
     * @var Mapping
     */
    protected $mapping;

    /**
     * @param PayloadMapperInterface $mapper
     * @param KatanaLogger $logger
     * @param Mapping $mapping
     */
    public function __construct(
        PayloadMapperInterface $mapper,
        KatanaLogger $logger,
        Mapping $mapping
    ) {
        $this->mapper = $mapper;
        $this->logger = $logger;
        $this->mapping = $mapping;
    }

    /**
     * @return InputExecutor
     */
    private function buildInputExecutor()
    {
        return new InputExecutor(
            new JsonResponder(),
            $this->mapper,
            $this->logger,
            $this->mapping
        );
    }

    /**
     * @param CliInput $input
     * @return ZeroMqLoopExecutor
     */
    private function buildZeroMqExecutor(CliInput $input)
    {
        $loop = Factory::create();
        $context = new Context($loop);

        $pcntl = new PCNTL($loop);
        $socket = $context->getSocket(ZMQ::SOCKET_REP);

        $this->logger->info("Binding to socket {$input->getSocket()}");
        $socket->bind("ipc://{$input->getSocket()}");

        $pcntl->on(SIGINT, function () use ($socket, $loop, $input) {
            $socket->unbind("ipc://{$input->getSocket()}");
            $loop->stop();
        });

        $pcntl->on(SIGTERM, function () use ($socket, $loop, $input) {
            $socket->unbind("ipc://{$input->getSocket()}");
            $loop->stop();
        });

        $serializer = new MessagePackSerializer();
        $responder = new ZeroMqMultipartResponder($serializer, $socket);

        return new ZeroMqLoopExecutor(
            $loop,
            $socket,
            $serializer,
            $responder,
            $this->mapper,
            $this->logger,
            $this->mapping
        );
    }

    /**
     * @param CliInput $input
     * @return AbstractExecutor
     */
    public function build(CliInput $input)
    {
        if ($input->hasInput()) {
            $this->logger->info('Setting up execution from input file');
            return $this->buildInputExecutor();

        } else {
            $this->logger->info('Setting up ZeroMQ loop execution');
            return $this->buildZeroMqExecutor($input);
        }
    }
}

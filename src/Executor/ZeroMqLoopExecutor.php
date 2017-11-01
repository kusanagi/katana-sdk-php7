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

namespace Katana\Sdk\Executor;

use Closure;
use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Mapper\SchemaMapper;
use Katana\Sdk\Messaging\MessagePackSerializer;
use Katana\Sdk\Messaging\Responder\ResponderInterface;
use Katana\Sdk\Schema\ActionSchema;
use Katana\Sdk\Schema\Mapping;

/**
 * Executor that sets up an event loop listening to ZeroMQ
 *
 * @package Katana\Sdk\Executor
 */
class ZeroMqLoopExecutor extends AbstractExecutor
{
    private $loop;

    private $socket;

    /**
     * @var MessagePackSerializer
     */
    private $serializer;

    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @return Closure
     */
    private function getErrorHandler()
    {
        return function($errno, $errstr) {
            $msg = "Language error ($errno) $errstr";
            $this->sendError($msg);
        };
    }

    /**
     * @return Closure
     */
    private function getShutdownFunction()
    {
        return function () {
            $error = error_get_last();
            if ($error && $error['type'] === E_ERROR) {
                $msg = "Language error (shutdown) ({$error['type']}) {$error['message']}";
                $this->sendError($msg);
            }
        };
    }

    /**
     * @param mixed $loop
     * @param mixed $socket
     * @param MessagePackSerializer $serializer
     * @param ResponderInterface $responder
     * @param PayloadWriterInterface $mapper
     * @param KatanaLogger $logger
     * @param Mapping $mapping
     */
    public function __construct(
        $loop,
        $socket,
        MessagePackSerializer $serializer,
        ResponderInterface $responder,
        PayloadWriterInterface $mapper,
        KatanaLogger $logger,
        Mapping $mapping
    ) {
        $this->loop = $loop;
        $this->socket = $socket;
        $this->serializer = $serializer;
        $this->mapping = $mapping;
        parent::__construct($responder, $mapper, $logger);
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     * @param callable $errorCallback
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks,
        callable $errorCallback = null
    ) {
        $this->socket->on(
            'messages',
            function ($message) use ($callbacks, $factory, $input, $errorCallback) {

                $msg = new MessagePackSerializer();
                list($action, $mappingPayload, $payload) = $message;

                if ($mappingPayload) {
                    $this->logger->info('Received new mapping');
                    $mapper = new SchemaMapper();
                    $services = [];
                    foreach ($msg->unserialize($mappingPayload) as $serviceName => $serviceData) {
                        foreach ($serviceData as $version => $service) {
                            $services[] = $mapper->getServiceSchema($serviceName, $version, $service);
                        }
                    }
                    $this->mapping->load($services);
                }

                if (!isset($callbacks[$action])) {
                    return $this->sendError("Unregistered callback $action");
                }

                $command = $msg->unserialize($payload);

                try {
                    $api = $factory->build($action, $command, $input, $this->mapping);
                } catch (\Throwable $e) {
                    $this->logger->error(
                        "{$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}"
                    );
                    return $this->sendError("Error parsing command");
                }
                $this->executeCallback($api, $action, $callbacks, $errorCallback);

                return true;
            }
        );

        register_shutdown_function($this->getShutdownFunction());
        $prevErrorHandler = set_error_handler($this->getErrorHandler(), E_ERROR);
        $this->loop->run();
        set_error_handler($prevErrorHandler);
    }
}

<?php

declare(strict_types=1);

namespace Katana\Sdk\Api;

use ATimer\Timer;
use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Executor\AbstractExecutor;
use Katana\Sdk\Executor\ExecutorFactory;
use Katana\Sdk\Logger\RequestKatanaLogger;
use Katana\Sdk\Mapper\CompactRuntimeCallMapper;
use Katana\Sdk\Mapper\CompactTransportMapper;
use Katana\Sdk\Messaging\MessagePackSerializer;
use Katana\Sdk\Messaging\RuntimeCaller\ZeroMQRuntimeCaller;
use Katana\Sdk\Schema\Mapping;
use ZMQ;
use ZMQContext;
use ZMQSocket;

final class WorkerAction extends ActionApi
{
    use ParamAccessorTrait;
    use ApiLoggerTrait;

    protected $actionName;

    /**
     * @var Transport
     */
    protected $transport;

    /**
     * @var TypeCatalog
     */
    protected $typeCatalog;

    /**
     * @var Mapping
     */
    protected $mapping;

    /**
     * @var ZeroMQRuntimeCaller|null
     */
    protected $caller;

    public function __construct(
        $name,
        $version,
        $actionName,
        Transport $transport,
        RequestKatanaLogger $logger,
        TypeCatalog $typeCatalog,
        Mapping $mapping,
        array $variables = [],
        array $params = []
    ) {
        $this->name = $name;
        $this->version = $version;
        $this->actionName = $actionName;
        $this->transport = $transport;
        $this->logger = $logger;
        $this->typeCatalog = $typeCatalog;
        $this->mapping = $mapping;
        $this->variables = $variables;
        $this->params = $this->prepareParams($params);
    }

    /**
     * @return RequestKatanaLogger
     */
    public function getLogger(): RequestKatanaLogger
    {
        return $this->logger;
    }

    public function call(
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = [],
        int $timeout = 10000
    ) {
        if (!$this->caller) {
            $context = new ZMQContext();
            $socket = new ZMQSocket($context, ZMQ::SOCKET_REQ);
            $socket->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);
            $transportMapper = new CompactTransportMapper();
            $runtimeCallMapper = new CompactRuntimeCallMapper($transportMapper);

            $this->caller = new ZeroMQRuntimeCaller(
                new MessagePackSerializer(),
                new CompactTransportMapper(),
                $socket,
                $runtimeCallMapper,
                new Timer()
            );
        }

        $address = 'ipc://@katana-' . preg_replace(
            '/[^a-zA-Z0-9-]/',
            '-',
            $this->getServiceSchema($this->name, $this->version)->getAddress()
        );

        return $this->caller->call(
            $this->name,
            $this->version,
            $this->actionName,
            new ActionTarget($service, new VersionString($version), $action),
            $this->transport,
            $address,
            $params,
            $files,
            $timeout
        );
    }
}

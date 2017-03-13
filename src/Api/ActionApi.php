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

namespace Katana\Sdk\Api;

use Katana\Sdk\Action;
use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Exception\SchemaException;
use Katana\Sdk\Exception\TransportException;
use Katana\Sdk\File as FileInterface;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\RuntimeCaller\ZeroMQRuntimeCaller;
use Katana\Sdk\Schema\Mapping;

class ActionApi extends Api implements Action
{
    use ParamAccessorTrait;

    /**
     * @var string
     */
    private $actionName;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * Copy of the Transport to send in runtime calls.
     *
     * @var Transport
     */
    private $transportCopy;

    /**
     * @var ZeroMQRuntimeCaller
     */
    private $caller;

    /**
     * @var mixed
     */
    private $return;

    /**
     * Action constructor.
     * @param KatanaLogger $logger
     * @param Component $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param array $variables
     * @param bool $debug
     * @param string $actionName
     * @param ZeroMQRuntimeCaller $caller
     * @param Transport $transport
     * @param Param[] $params
     */
    public function __construct(
        KatanaLogger $logger,
        Component $component,
        Mapping $mapping,
        string $path,
        string $name,
        string $version,
        string $frameworkVersion,
        array $variables,
        bool $debug,
        string $actionName,
        ZeroMQRuntimeCaller $caller,
        Transport $transport,
        array $params = []
    ) {
        parent::__construct(
            $logger,
            $component,
            $mapping,
            $path,
            $name,
            $version,
            $frameworkVersion,
            $variables,
            $debug
        );

        $this->actionName = $actionName;
        $this->caller = $caller;
        $this->transport = $transport;
        $this->transportCopy = clone $transport;
        $this->params = $this->prepareParams($params);
    }

    /**
     * @param array $value
     * @return bool
     */
    private function isArrayType(array $value): bool
    {
        return array_keys($value) === range(0, count($value) -1);
    }

    /**
     * @param array $value
     * @return bool
     */
    private function isObjectType(array $value): bool
    {
        return count(array_filter(array_keys($value), 'is_string')) === count($value);
    }

    /**
     * @return Transport
     */
    public function getTransport(): Transport
    {
        return $this->transport;
    }

    /**
     * @return bool
     */
    public function isOrigin(): bool
    {
        return $this->transport->getMeta()->getOrigin() === $this->name;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Action
     */
    public function setProperty(string $name, string $value): Action
    {
        $this->transport->getMeta()->setProperty($name, $value);

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile(string $name): bool
    {
        return $this->transport->hasFile($this->name, $this->version, $this->actionName, $name);
    }

    /**
     * @param string $name
     * @return FileInterface
     */
    public function getFile(string $name): FileInterface
    {
        if ($this->transport->hasFile($this->name, $this->version, $this->actionName, $name)) {
            return $this->transport->getFile($this->name, $this->version, $this->actionName, $name);
        } else {
            return $this->newFile($name, '');
        }
    }

    /**
     * @return FileInterface[]
     */
    public function getFiles(): array
    {
        $files = [];

        foreach ($this->transport->getFiles()->getAll() as $serviceFiles) {
            foreach ($serviceFiles as $versionFiles) {
                foreach ($versionFiles as $actionFiles) {
                    $files = array_merge($files, array_values($actionFiles));
                }
            }
        }

        return $files;
    }

    /**
     * @param string $name
     * @param string $path
     * @param string $mime
     * @return FileInterface
     */
    public function newFile(
        string $name,
        string $path,
        string $mime = ''
    ): FileInterface
    {
        return new File($name, $path, $mime);
    }

    /**
     * @param FileInterface $file
     * @return Action
     * @throws InvalidValueException
     * @throws SchemaException
     */
    public function setDownload(FileInterface $file): Action
    {
        $service = $this->getServiceSchema($this->name, $this->version);

        if ($file->isLocal() && !$service->hasFileServer()) {
            throw new InvalidValueException(sprintf(
                'File server not configured: "%s" (%s)',
                $this->name,
                $this->version
            ));
        }

        $this->transport->setBody($file);

        return $this;
    }

    /**
     * @param array $entity
     * @return Action
     * @throws TransportException
     */
    public function setEntity(array $entity): Action
    {
        if (!$this->isObjectType($entity)) {
            throw new TransportException('Unexpected collection');
        }

        $this->transport->setData($this->name, $this->version, $this->actionName, $entity);

        return $this;
    }

    /**
     * @param array $collection
     * @return Action
     * @throws TransportException
     */
    public function setCollection(array $collection): Action
    {
        if (!$this->isArrayType($collection)) {
            throw new TransportException('Unexpected entity');
        }

        $this->transport->setCollection($this->name, $this->version, $this->actionName, $collection);

        return $this;
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param string $foreignKey
     * @return Action
     */
    public function relateOne(
        string $primaryKey,
        string $service,
        string $foreignKey)
    : Action
    {
        $this->transport->addSimpleRelation($this->name, $primaryKey, $service, $foreignKey);

        return $this;
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param array $foreignKeys
     * @return Action
     */
    public function relateMany(
        string $primaryKey,
        string $service,
        array $foreignKeys)
    : Action
    {
        $this->transport->addMultipleRelation($this->name, $primaryKey, $service, $foreignKeys);

        return $this;
    }

    /**
     * @param string $link
     * @param string $uri
     * @return Action
     */
    public function setLink(string $link, string $uri): Action
    {
        $this->transport->setLink($this->name, $link, $uri);

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function commit(string $action, array $params = []): Action
    {
        $this->transport->addTransaction(
            new Transaction(
                'commit',
                new ServiceOrigin($this->name, $this->version),
                $this->actionName,
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function rollback(string $action, array $params = []): Action
    {
        $this->transport->addTransaction(
            new Transaction(
                'rollback',
                new ServiceOrigin($this->name, $this->version),
                $this->actionName,
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function complete(string $action, array $params = []): Action
    {
        $this->transport->addTransaction(
            new Transaction(
                'complete',
                new ServiceOrigin($this->name, $this->version),
                $this->actionName,
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $params
     * @param array $files
     * @param int $timeout
     * @return Action
     */
    public function call(
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = [],
        int $timeout = 1000
    ): Action
    {
        $address = 'ipc://@katana-' . preg_replace(
            '/[^a-zA-Z0-9-]/',
            '-',
            $this->getServiceSchema($this->name, $this->version)->getAddress()
        );

        return $this->caller->call(
            $this->actionName,
            new ActionTarget($service, new VersionString($version), $action),
            $this->transportCopy,
            $address,
            $params,
            $files,
            $timeout
        );
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @return Action
     * @throws InvalidValueException
     */
    public function deferCall(
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = []
    ): Action {
        $serviceSchema = $this->getServiceSchema($this->name, $this->version);
        $actionSchema = $serviceSchema->getActionSchema($this->actionName);

        if (!$actionSchema->hasDeferCall($service, $version, $action)) {
            throw new InvalidValueException(sprintf(
                'Deferred call not configured, connection to action on "%s" (%s) aborted: "%s"',
                $service,
                $version,
                $action
            ));
        }

        $versionString = new VersionString($version);
        $this->transport->addCall(new DeferCall(
            new ServiceOrigin($this->name, $this->version),
            $this->actionName,
            $service,
            $versionString,
            $action,
            $params
        ));

        foreach ($files as $file) {
            if ($file->isLocal() && !$serviceSchema->hasFileServer()) {
                throw new InvalidValueException(sprintf(
                    'File server not configured: "%s" (%s)',
                    $this->name,
                    $this->version
                ));
            }

            $this->transport->addFile($service, $versionString, $action, $file);
        }

        return $this;
    }

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $params
     * @param array $files
     * @param int $timeout
     * @return Action
     * @throws InvalidValueException
     */
    public function remoteCall(
        string $address,
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = [],
        int $timeout = 1000
    ): Action {
        $serviceSchema = $this->getServiceSchema($this->name, $this->version);
        $actionSchema = $serviceSchema->getActionSchema($this->actionName);

        if (!$actionSchema->hasRemoteCall($address, $service, $version, $action)) {
            throw new InvalidValueException(sprintf(
                'Remote call not configured, connection to action on ["%s"] "%s" (%s) aborted: "%s"',
                $address,
                $service,
                $version,
                $action
            ));
        }

        $versionString = new VersionString($version);
        $this->transport->addCall(new RemoteCall(
            new ServiceOrigin($this->name, $this->version),
            $this->actionName,
            $address,
            $service,
            $versionString,
            $action,
            $timeout,
            $params
        ));

        foreach ($files as $file) {
            if ($file->isLocal() && !$serviceSchema->hasFileServer()) {
                throw new InvalidValueException(sprintf(
                    'File server not configured: "%s" (%s)',
                    $this->name,
                    $this->version
                ));
            }

            $this->transport->addFile($service, $versionString, $action, $file);
        }

        return $this;
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return Action
     */
    public function error(
        string $message,
        int $code = 0,
        string $status = ''
    ): Action {
        $address = $this->transport->getMeta()->getGateway()[1];
        $this->transport->addError(new Error(
            $address,
            $this->name,
            $this->version,
            $message,
            $code,
            $status
        ));

        return $this;
    }

    /**
     * @param mixed $value
     * @return Action
     * @throws InvalidValueException
     */
    public function setReturn($value): Action
    {
        try {
            $service = $this->getServiceSchema($this->name, $this->version);
            $action = $service->getActionSchema($this->actionName);

            if (!$action->hasReturn()) {
                throw new InvalidValueException(sprintf(
                    'Cannot set a return value in "%s" (%s) for action: "%s"',
                    $this->name,
                    $this->version,
                    $this->actionName
                ));
            }

            if (!$this->validate($value, $action->getReturnType())) {
                throw new InvalidValueException(sprintf(
                    'Invalid return type given in "%s" (%s) for action: "%s"',
                    $this->name,
                    $this->version,
                    $this->actionName
                ));
            }
        } catch (SchemaException $e) {
            // This is to allow `service action` command which has no schema
        }

        $this->return = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasReturn(): bool
    {
        try {
            $service = $this->getServiceSchema($this->name, $this->version);
            $action = $service->getActionSchema($this->actionName);

            return $action->hasReturn();
        } catch (SchemaException $e) {
            // This is to allow `service action` command which has no schema
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        if ($this->return) {
            return $this->return;
        } else {
            $service = $this->getServiceSchema($this->name, $this->version);
            $action = $service->getActionSchema($this->actionName);

            return $this->getDefaultReturn($action->getReturnType());
        }
    }

    /**
     * @param string $type
     * @return mixed
     * @throws InvalidValueException
     */
    private function getDefaultReturn(string $type)
    {
        switch ($type) {
            case 'null':
                return null;
            case 'boolean':
                return false;
            case 'integer':
            case 'float':
                return 0;
            case 'string':
                return '';
            case 'array':
            case 'object':
                return [];
        }

        throw new InvalidValueException("Invalid value type: $type");
    }

    /**
     * @param mixed $value
     * @param string $type
     * @return bool
     * @throws InvalidValueException
     */
    private function validate($value, string $type): bool
    {
        switch ($type) {
            case 'null':
                return is_null($value);
            case 'boolean':
                return is_bool($value);
            case 'integer':
                return is_integer($value);
            case 'float':
                return is_float($value);
            case 'string':
                return is_string($value);
            case 'array':
                return $this->isArrayType($value);
            case 'object':
                return $this->isObjectType($value);
        }

        throw new InvalidValueException("Invalid value type: $type");
    }
}

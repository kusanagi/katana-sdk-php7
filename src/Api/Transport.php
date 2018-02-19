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

use Katana\Sdk\Api\Transport\ActionData;
use Katana\Sdk\Api\Transport\Callee;
use Katana\Sdk\Api\Transport\Caller;
use Katana\Sdk\Api\Transport\ForeignRelation;
use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Api\Transport\ServiceData;
use Katana\Sdk\Api\Transport\Transaction;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\File as FileInterface;

/**
 * Api class that encapsulates the full Transport
 *
 * @package Katana\Sdk\Api
 */
class Transport
{
    /**
     * @var TransportMeta
     */
    private $meta;

    /**
     * @var FileInterface
     */
    private $body;

    /**
     * @var TransportFiles
     */
    private $files;

    /**
     * @var ServiceData[]
     */
    private $data = [];

    /**
     * @var Relation[]
     */
    private $relations = [];

    /**
     * @var Link[]
     */
    private $links = [];

    /**
     * @var Caller[]
     */
    private $calls = [];

    /**
     * @var Transaction[]
     */
    private $transactions = [];

    /**
     * @var TransportErrors
     */
    private $errors;

    /**
     * Creates an empty Transport
     *
     * @return Transport
     */
    public static function newEmpty()
    {
        return new Transport(
            new TransportMeta('', '', '', '', [], 0, '', [], 0),
            new TransportFiles([]),
            [],
            [],
            [],
            [],
            [],
            new TransportErrors()
        );
    }

    /**
     * @param TransportMeta $meta
     * @param TransportFiles $files
     * @param ServiceData[] $data
     * @param Relation[] $relations
     * @param Link[] $links
     * @param Caller[] $calls
     * @param Transaction[] $transactions
     * @param TransportErrors $errors
     * @param FileInterface|null $body
     */
    public function __construct(
        TransportMeta $meta,
        TransportFiles $files,
        array $data,
        array $relations,
        array $links,
        array $calls,
        array $transactions,
        TransportErrors $errors,
        FileInterface $body = null
    ) {
        $this->meta = $meta;
        $this->files = $files;
        $this->data = $data;
        $this->relations = $relations;
        $this->links = $links;
        $this->calls = $calls;
        $this->transactions = $transactions;
        $this->errors = $errors;
        $this->body = $body;
    }

    /**
     * @return TransportMeta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function setBody(FileInterface $file)
    {
        $this->body = $file;

        return true;
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return isset($this->body);
    }

    /**
     * @return FileInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return ServiceData[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Relation[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function hasCalls(): bool
    {
        return count($this->calls) > 0;
    }

    /**
     * @return Caller[]
     */
    public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * @return bool
     */
    public function hasTransactions()
    {
        return count($this->transactions) > 0;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @return TransportErrors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param string $name
     * @return bool
     */
    public function hasFile($service, $version, $action, $name)
    {
        return $this->files->has($this->meta->getGateway()[1], $service, $version, $action, $name);
    }

    /**
     * @return bool
     */
    public function hasFiles()
    {
        return !empty($this->files->getAll());
    }

    /**
     * @return TransportFiles
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $service
     * @param VersionString $version
     * @param string $action
     * @param FileInterface $file
     * @return bool
     */
    public function addFile($service, VersionString $version, $action, FileInterface $file)
    {
        return $this->files->add($this->meta->getGateway()[1], $service, $version, $action, $file);
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param string $name
     * @return FileInterface
     */
    public function getFile($service, $version, $action, $name)
    {
        return $this->files->get($this->meta->getGateway()[1], $service, $version, $action, $name);
    }

    /**
     * @param string $address
     * @param string $name
     * @param string $version
     * @return int
     */
    private function findServiceData(string $address, string $name, string $version): int
    {
        $match = array_filter(
            $this->data,
            function (ServiceData $serviceData) use ($address, $name, $version) {
                return $serviceData->getAddress() === $address
                    && $serviceData->getName() === $name
                    && $serviceData->getVersion() === $version;
            }
        );

        if ($match) {
            return key($match);
        } else {
            return -1;
        }
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $data
     * @return ActionData
     * @throws InvalidValueException
     */
    public function setData($service, $version, $action, array $data): ActionData
    {
        $actionData = new ActionData($action, $data);

        $match = $this->findServiceData($this->meta->getGateway()[1], $service, $version);
        if ($match >= 0) {
            $dataActions =  $this->data[$match]->getActions();
            $dataActions[] = $actionData;
            $this->data[$match] = new ServiceData($this->meta->getGateway()[1], $service, $version, $dataActions);
        } else {
            $this->data[] = new ServiceData($this->meta->getGateway()[1], $service, $version, [$actionData]);
        }

        return $actionData;
    }

    /**
     * @param ServiceData[] $data
     */
    public function mergeData(ServiceData ...$data)
    {
        foreach ($data as $serviceData) {
            $match = $this->findServiceData($serviceData->getAddress(), $serviceData->getName(), $serviceData->getVersion());
            if ($match === -1) {
                $this->data[] = $serviceData;
            } else {
                $actions = array_merge($this->data[$match]->getActions(), $serviceData->getActions());
                $this->data[$match] = new ServiceData(
                    $serviceData->getAddress(),
                    $serviceData->getName(),
                    $serviceData->getVersion(),
                    $actions
                );
            }
        }
    }

    /**
     * @param string $address
     * @param string $name
     * @param string $primaryKey
     * @return int
     */
    private function findRelation(string $address, string $name, string $primaryKey): int
    {
        $match = array_filter(
            $this->relations,
            function (Relation $relation) use ($address, $name, $primaryKey) {
                return $relation->getAddress() === $address
                    && $relation->getName() === $name
                    && $relation->getPrimaryKey() === $primaryKey;
            }
        );

        if ($match) {
            return key($match);
        } else {
            return -1;
        }
    }

    /**
     * @param string $type
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param array $ids
     * @return bool
     * @throws InvalidValueException
     */
    private function addRelation(
        string $type,
        string $serviceFrom,
        string $idFrom,
        string $serviceTo,
        array $ids
    ): bool {
        $foreignRelation = new ForeignRelation(
            $this->meta->getGateway()[1],
            $serviceTo,
            $type,
            $ids
        );

        $match = $this->findRelation($this->meta->getGateway()[1], $serviceFrom, $idFrom);
        if ($match >= 0) {
            $foreignRelations =  $this->relations[$match]->getForeignRelations();
            $foreignRelations[] = $foreignRelation;
            $this->relations[$match] = new Relation(
                $this->meta->getGateway()[1],
                $serviceFrom,
                $idFrom,
                $foreignRelations
            );
        } else {
            $this->relations[] = new Relation(
                $this->meta->getGateway()[1],
                $serviceFrom,
                $idFrom,
                [$foreignRelation]
            );
        }

        return true;
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param string $idTo
     * @return bool
     * @throws InvalidValueException
     */
    public function addSimpleRelation($serviceFrom, $idFrom, $serviceTo, $idTo): bool
    {
        return $this->addRelation('one', $serviceFrom, $idFrom, $serviceTo, [$idTo]);
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param array $idsTo
     * @return bool
     * @throws InvalidValueException
     */
    public function addMultipleRelation($serviceFrom, $idFrom, $serviceTo, array $idsTo): bool
    {
        return $this->addRelation('many', $serviceFrom, $idFrom, $serviceTo, $idsTo);
    }

    /**
     * @param Relation[] $relations
     */
    public function mergeRelations(Relation ...$relations)
    {
        foreach ($relations as $relation) {
            $match = $this->findRelation($relation->getAddress(), $relation->getName(), $relation->getPrimaryKey());
            if ($match === -1) {
                $this->relations[] = $relation;
            } else {
                $foreignRelations = array_merge(
                    $this->relations[$match]->getForeignRelations(),
                    $relation->getForeignRelations()
                );
                $this->relations[$match] = new Relation(
                    $relation->getAddress(),
                    $relation->getName(),
                    $relation->getPrimaryKey(),
                    $foreignRelations
                );
            }
        }
    }

    /**
     * @param string $address
     * @param string $namespace
     * @param string $link
     * @return int
     */
    private function findLink(string $address, string $namespace, string $link): int
    {
        $match = array_filter(
            $this->links,
            function (Link $linkObject) use ($address, $namespace, $link) {
                return $linkObject->getAddress() === $address
                    && $linkObject->getName() === $namespace
                    && $linkObject->getLink() === $link;
            }
        );

        if ($match) {
            return key($match);
        } else {
            return -1;
        }
    }

    /**
     * @param string $namespace
     * @param string $link
     * @param string $uri
     * @return bool
     */
    public function setLink($namespace, $link, $uri): bool
    {
        $linkObject = new Link(
            $this->meta->getGateway()[1],
            $namespace,
            $link,
            $uri
        );

        $match = $this->findLink($this->meta->getGateway()[1], $namespace, $link);
        if ($match >= 0) {
            $this->links[$match] = $linkObject;
        } else {
            $this->links[] = $linkObject;
        }

        return true;
    }

    /**
     * @param Link[] $links
     */
    public function mergeLinks(Link ...$links)
    {
        foreach ($links as $link) {
            if ($this->findLink($link->getAddress(), $link->getName(), $link->getLink()) === -1) {
                $this->links[] = $link;
            }
        }
    }

    /**
     * @param \Katana\Sdk\Api\Transaction $transaction
     * @return bool
     * @throws InvalidValueException
     */
    public function addTransaction(\Katana\Sdk\Api\Transaction $transaction): bool
    {
        $this->transactions[] = new Transaction(
            $transaction->getType(),
            $transaction->getOrigin()->getName(),
            $transaction->getOrigin()->getVersion(),
            $transaction->getAction(),
            $transaction->getCallee(),
            $transaction->getParams()
        );

        return true;
    }

    /**
     * @param AbstractCall $call
     * @return bool
     */
    public function addCall(AbstractCall $call)
    {
        if ($call instanceof RemoteCall) {
            $calleeAddress = $call->getAddress();
            $timeout = $call->getTimeout();
        } else {
            $calleeAddress = '';
            $timeout = 0;
        }

        $this->calls[] = new Caller(
            $call->getOrigin()->getName(),
            $call->getOrigin()->getVersion(),
            $call->getCaller(),
            new Callee(
                $timeout,
                $call->getDuration(),
                $calleeAddress,
                $call->getService(),
                $call->getVersion(),
                $call->getAction(),
                $call->getParams()
            )
        );

        return true;
    }

    /**
     * @param Error $error
     * @return bool
     */
    public function addError(Error $error)
    {
        return $this->errors->add($error);
    }
}

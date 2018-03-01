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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Api\DeferCall;
use Katana\Sdk\Api\Error;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\ServiceOrigin;
use Katana\Sdk\Api\Transaction;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Transport\ActionData;
use Katana\Sdk\Api\Transport\Callee;
use Katana\Sdk\Api\Transport\Caller;
use Katana\Sdk\Api\Transport\ForeignRelation;
use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Api\Transport\ServiceData;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Exception\InvalidValueException;

class ExtendedTransportMapper implements TransportWriterInterface, TransportReaderInterface
{
    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['name'],
            $param['value'],
            $param['type'],
            true
        );
    }

    /**
     * @param Param $param
     * @return array
     */
    private function writeParam(Param $param)
    {
        return [
            'name' => $param->getName(),
            'version' => $param->getValue(),
            'type' => $param->getType(),
        ];
    }

    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw)
    {
        return new Transport(
            $this->getTransportMeta($raw),
            $this->getTransportFiles($raw),
            $this->getTransportData($raw),
            $this->getTransportRelations($raw),
            $this->getTransportLinks($raw),
            $this->getTransportCalls($raw),
            $this->getTransportTransactions($raw),
            $this->getTransportErrors($raw),
            $this->getTransportBody($raw)
        );
    }

    /**
     * @param Transport $transport
     * @return array
     */
    public function writeTransport(Transport $transport)
    {
        $output = [];
        $output = $this->writeTransportMeta($transport->getMeta(), $output);

        if ($transport->getFiles()->getAll()) {
            $output = $this->writeTransportFiles($transport->getFiles(), $output);
        }
        if ($transport->getData()->get()) {
            $output = $this->writeTransportData($transport->getData(), $output);
        }
        if ($transport->getRelations()->get()) {
            $output = $this->writeTransportRelations($transport->getRelations(), $output);
        }
        if ($transport->getLinks()) {
            $output = $this->writeTransportLinks($transport->getLinks(), $output);
        }
        if ($transport->getCalls()) {
            $output = $this->writeTransportCalls($transport->getCalls(), $output);
        }
        if ($transport->getTransactions()->get()) {
            $output = $this->writeTransportTransactions($transport->getTransactions(), $output);
        }
        if ($transport->getErrors()->get()) {
            $output = $this->writeTransportErrors($transport->getErrors(), $output);
        }
        if ($transport->hasBody()) {
            $output = $this->writeTransportBody($transport->getBody(), $output);
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportMeta
     */
    public function getTransportMeta(array $raw)
    {
        $rawMeta = $raw['meta'];

        return new TransportMeta(
            $rawMeta['version'],
            $rawMeta['id'],
            $rawMeta['datetime'],
            $rawMeta['start_time'],
            $rawMeta['end_time'],
            $rawMeta['duration'],
            $rawMeta['gateway'],
            $rawMeta['origin'],
            $rawMeta['level'],
            isset($rawMeta['properties'])? $rawMeta['properties'] : []
        );
    }

    /**
     * @param TransportMeta $meta
     * @param array $output
     * @return array
     */
    public function writeTransportMeta(TransportMeta $meta, array $output)
    {
        $output['meta'] = [
            'version' => $meta->getVersion(),
            'id' => $meta->getId(),
            'datetime' => $meta->getDatetime(),
            'start_time' => $meta->getStartTime(),
            'end_time' => $meta->getEndTime(),
            'duration' => $meta->getDuration(),
            'gateway' => $meta->getGateway(),
            'origin' => $meta->getOrigin(),
            'level' => $meta->getLevel(),
        ];

        if ($meta->hasProperties()) {
            $output['meta']['properties'] = $meta->getProperties();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportFiles
     */
    public function getTransportFiles(array $raw)
    {
        if (isset($raw['files'])) {
            $data = $raw['files'];
        } else {
            $data = [];
        }

        $files = [];
        foreach ($data as $service => $serviceFiles) {
            foreach ($serviceFiles as $version => $versionFiles) {
                foreach ($versionFiles as $action => $actionFiles) {
                    foreach ($actionFiles as $fileData) {
                        $data[$service][$version][$action][$fileData['name']] = new File(
                            $fileData['name'],
                            $fileData['path'],
                            $fileData['mime'],
                            $fileData['filename'],
                            $fileData['size'],
                            $fileData['token']
                        );
                    }
                }
            }
        }

        return new TransportFiles($files);
    }

    /**
     * @param TransportFiles $files
     * @param array $output
     * @return array
     */
    public function writeTransportFiles(TransportFiles $files, array $output)
    {
        foreach ($files->getAll() as $service => $serviceFiles) {
            foreach ($serviceFiles as $version => $versionFiles) {
                foreach ($versionFiles as $action => $actionFiles) {
                    /** @var File $file */
                    foreach ($actionFiles as $name => $file) {
                        $output['files'][$service][$version][$action][] = [
                            'name' => $file->getName(),
                            'path' => $file->getPath(),
                            'mime' => $file->getMime(),
                            'filename' => $file->getFilename(),
                            'size' => $file->getSize(),
                            'token' => $file->getToken(),
                        ];
                    }
                }
            }
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return File|null
     */
    public function getTransportBody(array $raw)
    {
        if (!isset($raw['body'])) {
            return null;
        }

        $rawBody = $raw['body'];

        return new File(
            'body',
            $rawBody['path'],
            $rawBody['mime'],
            $rawBody['filename'],
            $rawBody['size'],
            $rawBody['token']
        );
    }

    /**
     * @param File $body
     * @param array $output
     * @return array
     */
    public function writeTransportBody(File $body, array $output)
    {
        $output['body'] = [
            'path' => $body->getPath(),
            'mime' => $body->getMime(),
            'filename' => $body->getFilename(),
            'size' => $body->getSize(),
            'token' => $body->getToken(),
        ];

        return $output;
    }

    /**
     * @param array $raw
     * @return ServiceData[]
     * @throws InvalidValueException
     */
    public function getTransportData(array $raw): array
    {
        if (!isset($raw['data'])) {
            return [];
        }

        $datas = [];

        foreach ($raw['data'] as $address => $addressData) {
            foreach ($addressData as $name => $serviceData) {
                foreach ($serviceData as $version => $versionData) {
                    $actionDatas = [];
                    foreach ($versionData as $action => $actionData) {
                        foreach ($actionData as $data) {
                            $actionDatas[] = new ActionData($action, $data);
                        }
                    }
                    $datas[] = new ServiceData($address, $name, $version, $actionDatas);
                }
            }
        }

        return $datas;
    }

    /**
     * @param ServiceData[] $data
     * @param array $output
     * @return array
     */
    public function writeTransportData(array $data, array $output): array
    {
        foreach ($data as $serviceData) {
            foreach ($serviceData->getActions() as $actionData) {
                $output['data'][$serviceData->getAddress()][$serviceData->getName()][$serviceData->getVersion()][$actionData->getName()][] = $actionData->getData();
            }
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Relation[]
     * @throws InvalidValueException
     */
    public function getTransportRelations(array $raw): array
    {
        if (!isset($raw['relations'])) {
            return [];
        }

        $relations = [];

        foreach ($raw['relations'] as $addressFrom => $addressFromRelations) {
            foreach ($addressFromRelations as $serviceFrom => $serviceFromRelations) {
                foreach ($serviceFromRelations as $idFrom => $idFromRelations) {
                    $fromRelations = [];
                    foreach ($idFromRelations as $addressTo => $addressToRelations) {
                        foreach ($addressToRelations as $serviceTo => $serviceToRelations) {
                            $type = is_array($serviceToRelations) ? 'many' : 'one';
                            $fromRelations[] = new ForeignRelation($addressTo, $serviceTo, $type, (array) $serviceToRelations);
                        }
                    }
                    $relations[] = new Relation($addressFrom, $serviceFrom, $idFrom, $fromRelations);
                }
            }
        }

        return $relations;
    }

    /**
     * @param Relation[] $relations
     * @param array $output
     * @return array
     */
    public function writeTransportRelations(array $relations, array $output): array
    {
        foreach ($relations as $r) {
            foreach ($r->getForeignRelations() as $fr) {
                $foreignKeys = $fr->getForeignKeys();
                $output['relations'][$r->getAddress()][$r->getName()][$r->getPrimaryKey()][$fr->getAddress()][$fr->getName()] = $fr->getType() === 'one' ? $foreignKeys[0] : $foreignKeys;
            }
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Link[]
     */
    public function getTransportLinks(array $raw): array
    {
        if (!isset($raw['links'])) {
            return [];
        }

        $links = [];

        foreach ($raw['links'] as $address => $addressLinks) {
            foreach ($addressLinks as $name => $serviceLinks) {
                foreach ($serviceLinks as $link => $uri) {
                    $links[] = new Link($address, $name, $link, $uri);
                }
            }
        }

        return $links;
    }

    /**
     * @param Link[] $links
     * @param array $output
     * @return array
     */
    public function writeTransportLinks(array $links, array $output): array
    {
        $links = [];

        foreach ($links as $link) {
            $links[$link->getAddress()][$link->getName()][$link->getLink()] = $link->getUri();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Caller[]
     */
    public function getTransportCalls(array $raw): array
    {
        if (!isset($raw['calls'])) {
            return [];
        }

        $calls = [];
        foreach ($raw['calls'] as $service => $serviceCalls) {
            foreach ($serviceCalls as $version => $versionCalls) {
                $calls += array_map(function (array $callData) use ($service, $version) {
                    return new Caller(
                        $service,
                        $version,
                        $callData['caller'],
                        new Callee(
                            $callData['timeout'] ?? 0,
                            $callData['duration'] ?? 0,
                            $callData['gateway'] ?? '',
                            $callData['name'],
                            $callData['version'],
                            $callData['action'],
                            isset($callData['params'])? array_map([$this, 'getParam'], $callData['params']) : []
                        )
                    );
                }, $versionCalls);
            }
        }

        return $calls;
    }

    /**
     * @param Caller[] $calls
     * @param array $output
     * @return array
     */
    public function writeTransportCalls(array $calls, array $output): array
    {
        foreach ($calls as $caller) {
            $callee = $caller->getCallee();
            $callData = [
                'name' => $callee->getName(),
                'version' => $callee->getVersion(),
                'action' => $callee->getAction(),
                'duration' => $callee->getDuration(),
                'caller' => $caller->getAction(),
            ];

            if ($callee->isRemote()) {
                $callData['gateway'] = $callee->getAddress();
                $callData['timeout'] = $caller->getTimeout();
            }

            if ($callee->getParams()) {
                $callData['params'] = array_map([$this, 'writeParam'], $callee->getParams());
            }

            $output['calls'][$caller->getName()][$caller->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Transaction[]
     */
    public function getTransportTransactions(array $raw): array
    {
        if (!isset($raw['transactions'])) {
            return [];
        }

        $transactions = [];
        foreach ($raw['transactions'] as $type => $typeTransactions) {
            $transactions = array_merge($transactions, array_map(function ($transactionData) use ($type) {
                return new Transport\Transaction(
                    $type,
                    $transactionData['name'],
                    $transactionData['version'],
                    $transactionData['caller'],
                    $transactionData['action'],
                    isset($transactionData['params']) ? array_map([$this, 'getParam'], $transactionData['params']) : []
                );
            }, $typeTransactions));
        }

        return $transactions;
    }

    /**
     * @param Transaction[] $transactions
     * @param array $output
     * @return array
     */
    public function writeTransportTransactions(array $transactions, array $output): array
    {
        foreach ($transactions as $transaction) {
            $transactionData = [
                'name' => $transaction->getName(),
                'version' => $transaction->getVersion(),
                'caller' => $transaction->getCallerAction(),
                'action' => $transaction->getCalleeAction(),
            ];

            if ($transaction->getParams()) {
                $transactionData['params'] = array_map([$this, 'writeParam'], $transaction->getParams());
            }

            $output['t'][$transaction->getType()][] = $transactionData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Transport\Error[]
     */
    public function getTransportErrors(array $raw): array
    {
        if (!isset($raw['errors'])) {
            return [];
        }

        $errors = [];
        foreach ($raw['errors'] as $address => $addressErrors) {
            foreach ($addressErrors as $service => $serviceErrors) {
                foreach ($serviceErrors as $version => $versionErrors) {
                    $errors += array_map(function ($errorData) use ($address, $service, $version) {
                        return new Transport\Error(
                            $address,
                            $service,
                            $version,
                            $errorData['message'],
                            $errorData['code'],
                            $errorData['status']
                        );
                    }, $versionErrors);
                }
            }
        }

        return $errors;
    }

    /**
     * @param Error[] $errors
     * @param array $output
     * @return array
     */
    public function writeTransportErrors(array $errors, array $output): array
    {
        foreach ($errors as $error) {
            $errorData = [];
            if ($error->getMessage()) {
                $errorData['message'] = $error->getMessage();
            }
            if ($error->getCode()) {
                $errorData['code'] = $error->getCode();
            }
            if ($error->getStatus()) {
                $errorData['status'] = $error->getStatus();
            }
            $output['errors'][$error->getName()][$error->getVersion()][] = $errorData;
        }

        return $output;
    }
}

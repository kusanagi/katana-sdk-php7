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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Api\DeferCall;
use Katana\Sdk\Api\Error;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\RemoteCall;
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

class CompactTransportMapper implements TransportWriterInterface, TransportReaderInterface
{
    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['n'],
            $param['v'],
            $param['t'],
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
            'n' => $param->getName(),
            'v' => $param->getValue(),
            't' => $param->getType(),
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
        if ($transport->getData()) {
            $output = $this->writeTransportData($transport->getData(), $output);
        }
        if ($transport->getRelations()) {
            $output = $this->writeTransportRelations($transport->getRelations(), $output);
        }
        if ($transport->getLinks()) {
            $output = $this->writeTransportLinks($transport->getLinks(), $output);
        }
        if ($transport->getCalls()) {
            $output = $this->writeTransportCalls($transport->getCalls(), $output);
        }
        if ($transport->getTransactions()) {
            $output = $this->writeTransportTransactions($transport->getTransactions(), $output);
        }
        if ($transport->getErrors()) {
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
        $rawMeta = $raw['m'];

        return new TransportMeta(
            $rawMeta['v'],
            $rawMeta['i'],
            $rawMeta['d'],
            $rawMeta['s'] ?? '',
            $rawMeta['e'] ?? '',
            $rawMeta['D'] ?? 0,
            $rawMeta['g'],
            $rawMeta['o'],
            $rawMeta['l'],
            isset($rawMeta['p'])? $rawMeta['p'] : []
        );
    }

    /**
     * @param TransportMeta $meta
     * @param array $output
     * @return array
     */
    public function writeTransportMeta(TransportMeta $meta, array $output)
    {
        $output['m'] = [
            'v' => $meta->getVersion(),
            'i' => $meta->getId(),
            'd' => $meta->getDatetime(),
            's' => $meta->getStartTime(),
            'e' => $meta->getEndTime(),
            'D' => $meta->getDuration(),
            'g' => $meta->getGateway(),
            'o' => $meta->getOrigin(),
            'l' => $meta->getLevel(),
        ];

        if ($meta->hasProperties()) {
            $output['m']['p'] = $meta->getProperties();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportFiles
     */
    public function getTransportFiles(array $raw)
    {
        if (isset($raw['f'])) {
            $data = $raw['f'];
        } else {
            $data = [];
        }

        $files = [];
        foreach ($data as $address => $addressFiles) {
            foreach ($addressFiles as $service => $serviceFiles) {
                foreach ($serviceFiles as $version => $versionFiles) {
                    foreach ($versionFiles as $action => $actionFiles) {
                        foreach ($actionFiles as $fileData) {
                            $token = isset($fileData['t']) ? $fileData['t'] : '';
                            $files[$address][$service][$version][$action][$fileData['n']] = new File(
                                $fileData['n'],
                                $fileData['p'],
                                $fileData['m'],
                                $fileData['f'],
                                $fileData['s'],
                                $token
                            );
                        }
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
        foreach ($files->getAll() as $address => $addressFiles) {
            foreach ($addressFiles as $service => $serviceFiles) {
                foreach ($serviceFiles as $version => $versionFiles) {
                    foreach ($versionFiles as $action => $actionFiles) {
                        /** @var File $file */
                        foreach ($actionFiles as $name => $file) {
                            $output['f'][$address][$service][$version][$action][] = [
                                'n' => $file->getName(),
                                'p' => $file->getPath(),
                                'm' => $file->getMime(),
                                'f' => $file->getFilename(),
                                's' => $file->getSize(),
                                't' => $file->getToken(),
                            ];
                        }
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
        if (!isset($raw['b'])) {
            return null;
        }

        $rawBody = $raw['b'];

        return new File(
            'body',
            $rawBody['p'],
            $rawBody['m'],
            $rawBody['f'],
            $rawBody['s'],
            $rawBody['t']
        );
    }

    /**
     * @param File $body
     * @param array $output
     * @return array
     */
    public function writeTransportBody(File $body, array $output)
    {
        $output['b'] = [
            'p' => $body->getPath(),
            'm' => $body->getMime(),
            'f' => $body->getFilename(),
            's' => $body->getSize(),
            't' => $body->getToken(),
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
        if (!isset($raw['d'])) {
            return [];
        }

        $datas = [];

        foreach ($raw['d'] as $address => $addressData) {
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
                $output['d'][$serviceData->getAddress()][$serviceData->getName()][$serviceData->getVersion()][$actionData->getName()][] = $actionData->getData();
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
        if (!isset($raw['r'])) {
            return [];
        }

        $relations = [];

        foreach ($raw['r'] as $addressFrom => $addressFromRelations) {
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
                $output['r'][$r->getAddress()][$r->getName()][$r->getPrimaryKey()][$fr->getAddress()][$fr->getName()] = $fr->getForeignKeys();
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
        if (!isset($raw['l'])) {
            return [];
        }

        $links = [];

        foreach ($raw['l'] as $address => $addressLinks) {
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
        foreach ($links as $link) {
            $output['l'][$link->getAddress()][$link->getName()][$link->getLink()] = $link->getUri();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Caller[]
     */
    public function getTransportCalls(array $raw): array
    {
        if (!isset($raw['C'])) {
            return [];
        }

        $calls = [];
        foreach ($raw['C'] as $service => $serviceCalls) {
            foreach ($serviceCalls as $version => $versionCalls) {
                $calls += array_map(function (array $callData) use ($service, $version) {
                    return new Caller(
                        $service,
                        $version,
                        $callData['C'],
                        new Callee(
                            $callData['x'] ?? 0,
                            $callData['D'] ?? 0,
                            $callData['g'] ?? '',
                            $callData['n'],
                            $callData['v'],
                            $callData['a'],
                            isset($callData['p'])? array_map([$this, 'getParam'], $callData['p']) : []
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
                'n' => $callee->getName(),
                'v' => $callee->getVersion(),
                'a' => $callee->getAction(),
                'D' => $callee->getDuration(),
                'C' => $caller->getAction(),
            ];

            if ($callee->isRemote()) {
                $callData['g'] = $callee->getAddress();
                $callData['x'] = $caller->getTimeout();
            }

            if ($callee->getParams()) {
                $callData['p'] = array_map([$this, 'writeParam'], $callee->getParams());
            }

            $output['C'][$caller->getName()][$caller->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Transport\Transaction[]
     */
    public function getTransportTransactions(array $raw): array
    {
        if (!isset($raw['t'])) {
            return [];
        }

        $transactions = [];
        foreach ($raw['t'] as $type => $typeTransactions) {
            $transactions = array_merge($transactions, array_map(function ($transactionData) use ($type) {
                $type = [
                    'c' => 'commit',
                    'r' => 'rollback',
                    'C' => 'complete',
                ][$type];

                return new Transport\Transaction(
                    $type,
                    $transactionData['n'],
                    $transactionData['v'],
                    $transactionData['C'],
                    $transactionData['a'],
                    isset($transactionData['p']) ? array_map([$this, 'getParam'], $transactionData['p']) : []
                );
            }, $typeTransactions));
        }

        return $transactions;
    }

    /**
     * @param Transport\Transaction[] $transactions
     * @param array $output
     * @return array
     */
    public function writeTransportTransactions(array $transactions, array $output): array
    {
        foreach ($transactions as $transaction) {
            $transactionData = [
                's' => $transaction->getName(),
                'v' => $transaction->getVersion(),
                'C' => $transaction->getCallerAction(),
                'a' => $transaction->getCalleeAction(),
            ];

            if ($transaction->getParams()) {
                $transactionData['p'] = array_map([$this, 'writeParam'], $transaction->getParams());
            }

            $type = [
                'commit' => 'c',
                'rollback' => 'r',
                'complete' => 'C',
            ][$transaction->getType()];

            $output['t'][$type][] = $transactionData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return Error[]
     */
    public function getTransportErrors(array $raw): array
    {
        if (!isset($raw['e'])) {
            return [];
        }

        $errors = [];
        foreach ($raw['e'] as $address => $addressErrors) {
            foreach ($addressErrors as $service => $serviceErrors) {
                foreach ($serviceErrors as $version => $versionErrors) {
                    $errors += array_map(function ($errorData) use ($address, $service, $version) {
                        return new Transport\Error(
                            $address,
                            $service,
                            $version,
                            $errorData['m'],
                            $errorData['c'],
                            $errorData['s']
                        );
                    }, $versionErrors);
                }
            }
        }

        return $errors;
    }

    /**
     * @param Transport\Error[] $errors
     * @param array $output
     * @return array
     */
    public function writeTransportErrors(array $errors, array $output): array
    {
        foreach ($errors as $error) {
            $errorData = [];
            if ($error->getMessage()) {
                $errorData['m'] = $error->getMessage();
            }
            if ($error->getCode()) {
                $errorData['c'] = $error->getCode();
            }
            if ($error->getStatus()) {
                $errorData['s'] = $error->getStatus();
            }
            $output['e'][$error->getAddress()][$error->getService()][$error->getVersion()][] = $errorData;
        }

        return $output;
    }

    /**
     * @param Transport $transport
     * @param array $mergeData
     * @throws InvalidValueException
     */
    public function merge(Transport $transport, array $mergeData)
    {
        // Merge meta properties and fallbacks
        $newProperties = array_diff_key(
            $mergeData['m']['p'] ?? [],
            $transport->getMeta()->getProperties()
        );
        foreach ($newProperties as $key => $value) {
            $transport->getMeta()->setProperty($key, $value);
        }

        $transport->getMeta()->setFallbacks(
            array_merge_recursive(
                $transport->getMeta()->getFallbacks(),
                $mergeData['m']['f'] ?? []
            )
        );

        // Merge data
        $data = [];
        foreach ($mergeData['d'] ?? [] as $address => $aData) {
            foreach ($aData as $service => $sData) {
                foreach ($sData as $version => $vData) {
                    $actions = [];
                    foreach ($vData as $action => $data) {
                        $actions[] = new ActionData($action, $data);
                    }
                    $data[] = new ServiceData($address, $service, $version, $actions);
                }
            }
        }

        // Merge relations
        $relations = [];
        foreach ($mergeData['r'] ?? [] as $addressFrom => $addressFromRelations) {
            foreach ($addressFromRelations as $serviceFrom => $serviceFromRelations) {
                foreach ($serviceFromRelations as $idFrom => $idFromRelations) {
                    $foreignRelations = [];
                    foreach ($idFromRelations as $addressTo => $addressToRelations) {
                        foreach ($addressToRelations as $serviceTo => $foreignKeys) {
                            $foreignRelations[] = new ForeignRelation(
                                $addressTo,
                                $serviceTo,
                                is_array($foreignKeys) ? 'many' : 'one',
                                (array) $foreignKeys
                            );
                        }
                    }
                    $relations[] = new Relation(
                        $addressFrom,
                        $serviceFrom,
                        $idFrom,
                        $foreignRelations
                    );
                }
            }
        }
        $transport->mergeRelations(...$relations);

        // Merge links
        $links = [];
        foreach ($mergeData['l'] ?? [] as $address => $aLinks) {
            foreach ($aLinks as $namespace => $nLinks) {
                foreach ($nLinks as $name => $link) {
                    $links[] = new Link($address, $namespace, $name, $link);
                }
            }
        }
        $transport->mergeLinks(...$links);

        // Merge calls
        foreach ($mergeData['C'] ?? [] as $service => $sCalls) {
            foreach ($sCalls as $version => $vCalls) {
                foreach ($vCalls as $vCall) {
                    if (!isset($vCall['D']) || $vCall['D'] === 0) {
                        continue;
                    }

                    if (isset($vCall['g'])) {
                        $call = new RemoteCall(
                            new ServiceOrigin($service, $version),
                            $vCall['C'],
                            $vCall['g'],
                            $vCall['n'],
                            new VersionString($vCall['v']),
                            $vCall['a'],
                            $vCall['D'] ?? 0,
                            $vCall['t'],
                            isset($vCall['p']) ? array_map([$this, 'getParam'], $vCall['p']) : []
                        );
                    } else {
                        $call = new DeferCall(
                            new ServiceOrigin($service, $version),
                            $vCall['C'],
                            $vCall['n'],
                            new VersionString($vCall['v']),
                            $vCall['a'],
                            $vCall['D'] ?? 0,
                            isset($vCall['p']) ? array_map([$this, 'getParam'], $vCall['p']) : []
                        );
                    }
                    $transport->addCall($call);
                }
            }
        }

        // Merge Transactions
        foreach ($mergeData['t'] ?? [] as $type => $transaction) {
            $type = [
                'c' => 'commit',
                'r' => 'rollback',
                'C' => 'complete',
            ][$type];
            $transport->addTransaction(new Transaction(
                $type,
                new ServiceOrigin($transaction['n'], $transaction['v']),
                $transaction['a'],
                $transaction['c'],
                isset($transaction['p']) ? array_map([$this, 'getParam'], $transaction['p']) : []
            ));
        }

        // Merge errors
        foreach ($mergeData['e'] ?? [] as $address => $aErrors) {
            foreach ($aErrors as $service => $sErrors) {
                foreach ($sErrors as $version => $vErrors) {
                    foreach ($vErrors as $error) {
                        $transport->addError(new Error(
                            $address,
                            $service,
                            $version,
                            $error['m'],
                            $error['c'],
                            $error['s']
                        ));
                    }
                }
            }
        }

        // Merge Body
        if (!$transport->hasBody() && isset($mergeData['b'])) {
            $transport->setBody(new File(
                'body',
                $mergeData['b']['p'],
                $mergeData['b']['m'],
                $mergeData['b']['f'],
                $mergeData['b']['s'],
                $mergeData['b']['t']
            ));
        }

        $files = $transport->getFiles();
        foreach ($mergeData['f'] ?? [] as $address => $aFiles) {
            foreach ($aFiles as $service => $sFiles) {
                foreach ($sFiles as $version => $vFiles) {
                    foreach ($vFiles as $action => $aFiles) {
                        foreach ($aFiles as $file) {
                            if ($files->has($address, $service, $version, $action, $file['n'])) {
                                continue;
                            }

                            $transport->getFiles()->add(
                                $address,
                                $service,
                                new VersionString($version),
                                $action,
                                new File(
                                    $file['n'],
                                    $file['p'],
                                    $file['m'],
                                    $file['f'],
                                    $file['s'],
                                    $file['t']
                                )
                            );
                        }
                    }
                }
            }
        }
    }
}

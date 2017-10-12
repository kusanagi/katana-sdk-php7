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
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;
use Katana\Sdk\Api\Value\VersionString;

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
        $output = $this->writeTransportFiles($transport->getFiles(), $output);
        $output = $this->writeTransportData($transport->getData(), $output);
        $output = $this->writeTransportRelations($transport->getRelations(), $output);
        $output = $this->writeTransportLinks($transport->getLinks(), $output);
        $output = $this->writeTransportCalls($transport->getCalls(), $output);
        $output = $this->writeTransportTransactions($transport->getTransactions(), $output);
        $output = $this->writeTransportErrors($transport->getErrors(), $output);
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
     * @return TransportData
     */
    public function getTransportData(array $raw)
    {
        if (isset($raw['d'])) {
            $data = $raw['d'];
        } else {
            $data = [];
        }

        return new TransportData($data);
    }

    /**
     * @param TransportData $data
     * @param array $output
     * @return array
     */
    public function writeTransportData(TransportData $data, array $output)
    {
        if ($data->get()) {
            $output['d'] = $data->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportRelations
     */
    public function getTransportRelations(array $raw)
    {
        if (isset($raw['r']) && (array) $raw['r']) {
            $relations = $raw['r'];
        } else {
            $relations = [];
        }

        return new TransportRelations($relations);
    }

    /**
     * @param TransportRelations $relations
     * @param array $output
     * @return array
     */
    public function writeTransportRelations(TransportRelations $relations, array $output)
    {
        if ($relations->get()) {
            $output['r'] = $relations->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportLinks
     */
    public function getTransportLinks(array $raw)
    {
        if (isset($raw['l']) && (array) $raw['l']) {
            $links = $raw['l'];
        } else {
            $links = [];
        }

        return new TransportLinks($links);
    }

    /**
     * @param TransportLinks $links
     * @param array $output
     * @return array
     */
    public function writeTransportLinks(TransportLinks $links, array $output)
    {
        if ($links->get()) {
            $output['l'] = $links->get();
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportCalls
     */
    public function getTransportCalls(array $raw)
    {
        if (isset($raw['C']) && (array) $raw['C']) {
            $rawCalls = $raw['C'];
        } else {
            $rawCalls = [];
        }

        $calls = [];
        foreach ($rawCalls as $service => $serviceCalls) {
            foreach ($serviceCalls as $version => $versionCalls) {
                $calls += array_map(function (array $callData) use ($service, $version) {
                    return new DeferCall(
                        new ServiceOrigin($service, $version),
                        $callData['C'],
                        $callData['n'],
                        new VersionString($callData['v']),
                        $callData['a'],
                        $callData['D'] ?? 0,
                        isset($callData['p'])? array_map([$this, 'getParam'], $callData['p']) : []
                    );
                }, $versionCalls);
            }
        }

        return new TransportCalls($calls);
    }

    /**
     * @param TransportCalls $calls
     * @param array $output
     * @return array
     */
    public function writeTransportCalls(TransportCalls $calls, array $output)
    {
        foreach ($calls->get() as $call) {
            $callData = [
                'n' => $call->getService(),
                'v' => $call->getVersion(),
                'a' => $call->getAction(),
                'D' => $call->getDuration(),
                'C' => $call->getCaller(),
            ];

            if ($call instanceof RemoteCall) {
                $callData['g'] = $call->getAddress();
                $callData['t'] = $call->getTimeout();
            }

            if ($call->getParams()) {
                $callData['p'] = array_map([$this, 'writeParam'], $call->getParams());
            } else {
                $callData['p'] = [];
            }

            $output['C'][$call->getOrigin()->getName()][$call->getOrigin()->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportTransactions
     */
    public function getTransportTransactions(array $raw)
    {
        if (isset($raw['t'])) {
            $rawTransactions = $raw['t'];
        } else {
            $rawTransactions = [];
        }

        $transactions = [];
        foreach ($rawTransactions as $type => $typeTransactions) {
            $transactions = array_merge($transactions, array_map(function ($transactionData) use ($type) {
                $type = [
                    'c' => 'commit',
                    'r' => 'rollback',
                    'C' => 'complete',
                ][$type];

                return new Transaction(
                    $type,
                    new ServiceOrigin($transactionData['n'], $transactionData['v']),
                    $transactionData['a'],
                    $transactionData['c'],
                    isset($transactionData['p']) ? array_map([$this, 'getParam'], $transactionData['p']) : []
                );
            }, $typeTransactions));
        }

        return new TransportTransactions($transactions);
    }

    /**
     * @param TransportTransactions $transactions
     * @param array $output
     * @return array
     */
    public function writeTransportTransactions(TransportTransactions $transactions, array $output)
    {
        foreach ($transactions->get() as $transaction) {
            $transactionData = [
                's' => $transaction->getOrigin()->getName(),
                'v' => $transaction->getOrigin()->getVersion(),
                'a' => $transaction->getAction(),
                'c' => $transaction->getCallee(),
            ];

            if ($transaction->getParams()) {
                $transactionData['p'] = array_map([$this, 'writeParam'], $transaction->getParams());
            } else {
                // todo: remove when katana makes parameters optional
                $transactionData['p'] = [];
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
     * @return TransportErrors
     */
    public function getTransportErrors(array $raw)
    {
        if (isset($raw['e'])) {
            $rawErrors = $raw['e'];
        } else {
            $rawErrors = [];
        }

        $errors = [];
        foreach ($rawErrors as $address => $addressErrors) {
            foreach ($addressErrors as $service => $serviceErrors) {
                foreach ($serviceErrors as $version => $versionErrors) {
                    $errors += array_map(function ($errorData) use ($address, $service, $version) {
                        return new Error(
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

        return new TransportErrors($errors);
    }

    /**
     * @param TransportErrors $errors
     * @param array $output
     * @return array
     */
    public function writeTransportErrors(TransportErrors $errors, array $output)
    {
        foreach ($errors->get() as $error) {
            $output['e'][$error->getAddress()][$error->getService()][$error->getVersion()][] = [
                'm' => $error->getMessage(),
                'c' => $error->getCode(),
                's' => $error->getStatus(),
            ];
        }

        return $output;
    }

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
        foreach ($mergeData['d'] ?? [] as $address => $aData) {
            foreach ($aData as $service => $sData) {
                foreach ($sData as $version => $vData) {
                    foreach ($vData as $action => $data) {
                        $transport->getData()->set(
                            $address, $service, $version, $action, $data
                        );
                    }
                }
            }
        }

        // Merge relations
        $relations = $transport->getRelations()->get();
        foreach ($mergeData['r'] ?? [] as $address1 => $a1Relations) {
            foreach ($a1Relations as $service1 => $s1Relations) {
                foreach ($s1Relations as $id1 => $i1Relations) {
                    foreach ($i1Relations as $address2 => $a2Relations) {
                        foreach ($a2Relations as $service2 => $s2Relations) {
                            if (isset($relations[$address1][$service1][$id1][$address2][$service2])) {
                                continue;
                            }

                            if (is_array($s2Relations)) {
                                $transport->getRelations()->addMultipleRelation(
                                    $address1,
                                    $service1,
                                    $id1,
                                    $address2,
                                    $service2,
                                    $s2Relations
                                );
                            } else {
                                $transport->getRelations()->addSimple(
                                    $address1,
                                    $service1,
                                    $id1,
                                    $address2,
                                    $service2,
                                    $s2Relations
                                );
                            }
                        }
                    }
                }
            }
        }

        // Merge links
        $links = $transport->getLinks()->get();
        foreach ($mergeData['l'] ?? [] as $address => $aLinks) {
            foreach ($aLinks as $namespace => $nLinks) {
                foreach ($nLinks as $name => $link) {
                    if (!isset($links[$address][$namespace][$name])) {
                        $transport->getLinks()->setLink($address, $namespace, $name, $link);
                    }
                }
            }
        }

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

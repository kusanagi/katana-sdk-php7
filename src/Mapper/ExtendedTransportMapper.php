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
        if ($transport->getLinks()->get()) {
            $output = $this->writeTransportLinks($transport->getLinks(), $output);
        }
        if ($transport->getCalls()->get()) {
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
     * @return TransportData|null
     */
    public function getTransportData(array $raw)
    {
        if (isset($raw['data'])) {
            $data = $raw['data'];
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
        $output['data'] = $data->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportRelations
     */
    public function getTransportRelations(array $raw)
    {
        if (isset($raw['relations'])) {
            $relations = $raw['relations'];
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
        $output['relations'] = $relations->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportLinks
     */
    public function getTransportLinks(array $raw)
    {
        if (isset($raw['links'])) {
            $links = $raw['links'];
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
        $output['links'] = $links->get();

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportCalls
     */
    public function getTransportCalls(array $raw)
    {
        if (isset($raw['calls'])) {
            $rawCalls = $raw['calls'];
        } else {
            $rawCalls = [];
        }

        $calls = [];
        foreach ($rawCalls as $address => $addressCalls) {
            foreach ($addressCalls as $service => $serviceCalls) {
                foreach ($serviceCalls as $version => $versionCalls) {
                    $calls += array_map(function (array $callData) use ($address, $service, $version) {
                        return new DeferCall(
                            new ServiceOrigin($service, $version),
                            $callData['caller'],
                            $callData['name'],
                            new VersionString($callData['version']),
                            $callData['action'],
                            $callData['duration'],
                            isset($callData['params'])? array_map([$this, 'getParam'], $callData['params']) : []
                        );
                    }, $versionCalls);
                }
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
                'name' => $call->getService(),
                'version' => $call->getVersion(),
                'action' => $call->getAction(),
                'duration' => $call->getDuration(),
                'caller' => $call->getCaller(),
            ];

            if ($call->getParams()) {
                $callData['params'] = array_map([$this, 'writeParam'], $call->getParams());
            }

            $output['calls'][$call->getOrigin()->getName()][$call->getOrigin()->getVersion()][] = $callData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportTransactions
     */
    public function getTransportTransactions(array $raw)
    {
        if (isset($raw['transactions'])) {
            $rawTransactions = $raw['transactions'];
        } else {
            $rawTransactions = [];
        }

        $transactions = [];
        foreach ($rawTransactions as $address => $addressTransactions) {
            foreach ($addressTransactions as $type => $typeTransactions) {
                $transactions += array_map(function ($transactionData) use ($address, $type) {
                    return new Transaction(
                        $type,
                        new ServiceOrigin($transactionData['service'], $transactionData['version']),
                        $transactionData['action'],
                        $transactionData['callee'],
                        isset($transactionData['params']) ? array_map([$this, 'getParam'], $transactionData['params']) : []
                    );
                }, $typeTransactions);
            }
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
                'service' => $transaction->getOrigin()->getName(),
                'version' => $transaction->getOrigin()->getVersion(),
                'action' => $transaction->getAction(),
                'callee' => $transaction->getCallee(),
            ];

            if ($transaction->getParams()) {
                $transactionData['params'] = array_map([$this, 'writeParam'], $transaction->getParams());
            }

            $type = $transaction->getType();

            $output['transactions'][$transaction->getOrigin()->getName()][$type][] = $transactionData;
        }

        return $output;
    }

    /**
     * @param array $raw
     * @return TransportErrors
     */
    public function getTransportErrors(array $raw)
    {
        if (isset($raw['errors'])) {
            $rawErrors = $raw['errors'];
        } else {
            $rawErrors = [];
        }

        $errors = [];
        foreach ($rawErrors as $service => $serviceErrors) {
            foreach ($serviceErrors as $version => $versionErrors) {
                $errors += array_map(function ($errorData) use ($service, $version) {
                    return new Error(
                        $service,
                        $version,
                        $errorData['message'],
                        $errorData['code'],
                        $errorData['status']
                    );
                }, $versionErrors);
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
            $output['errors'][$error->getService()][$error->getVersion()][] = $errorData;
        }

        return $output;
    }
}

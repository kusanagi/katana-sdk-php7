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

use Katana\Sdk\Api\Value\VersionString;
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
     * @var TransportData
     */
    private $data;

    /**
     * @var TransportRelations
     */
    private $relations;

    /**
     * @var TransportLinks
     */
    private $links;

    /**
     * @var TransportCalls
     */
    private $calls;

    /**
     * @var TransportTransactions
     */
    private $transactions;

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
            new TransportData(),
            new TransportRelations(),
            new TransportLinks(),
            new TransportCalls(),
            new TransportTransactions(),
            new TransportErrors()
        );
    }

    /**
     * @param TransportMeta $meta
     * @param TransportFiles $files
     * @param TransportData $data
     * @param TransportRelations $relations
     * @param TransportLinks $links
     * @param TransportCalls $calls
     * @param TransportTransactions $transactions
     * @param TransportErrors $errors
     * @param FileInterface|null $body
     */
    public function __construct(
        TransportMeta $meta,
        TransportFiles $files,
        TransportData $data,
        TransportRelations $relations,
        TransportLinks $links,
        TransportCalls $calls,
        TransportTransactions $transactions,
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
     * @return TransportData
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return TransportRelations
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @return TransportLinks
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function hasCalls()
    {
        return $this->calls->has();
    }

    /**
     * @return TransportCalls
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @return bool
     */
    public function hasTransactions()
    {
        return $this->transactions->has();
    }

    /**
     * @return TransportTransactions
     */
    public function getTransactions()
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
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $data
     * @return bool
     */
    public function setData($service, $version, $action, array $data)
    {
        return $this->data->set($this->meta->getGateway()[1], $service, $version, $action, $data);
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $collection
     * @return bool
     */
    public function setCollection($service, $version, $action, array $collection)
    {
        return $this->data->set($this->meta->getGateway()[1], $service, $version, $action, $collection);
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param string $idTo
     * @return bool
     */
    public function addSimpleRelation($serviceFrom, $idFrom, $serviceTo, $idTo)
    {
        return $this->relations->addSimple(
            $this->meta->getGateway()[1],
            $serviceFrom,
            $idFrom,
            $this->meta->getGateway()[1],
            $serviceTo,
            $idTo
        );
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param array $idsTo
     * @return bool
     */
    public function addMultipleRelation($serviceFrom, $idFrom, $serviceTo, array $idsTo)
    {
        return $this->relations->addMultipleRelation(
            $this->meta->getGateway()[1],
            $serviceFrom,
            $idFrom,
            $this->meta->getGateway()[1],
            $serviceTo,
            $idsTo
        );
    }

    /**
     * @param string $namespace
     * @param string $link
     * @param string $uri
     * @return bool
     */
    public function setLink($namespace, $link, $uri)
    {
        return $this->links->setLink($this->meta->getGateway()[1], $namespace, $link, $uri);
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function addTransaction(Transaction $transaction)
    {
        return $this->transactions->add($transaction);
    }

    /**
     * @param DeferCall $call
     * @return bool
     */
    public function addCall(DeferCall $call)
    {
        return $this->calls->add($call);
    }

    /**
     * @param Error $error
     * @return bool
     */
    public function addError(Error $error)
    {
        return $this->errors->add($error);
    }



    public function replace(
        TransportMeta $meta,
        TransportFiles $files,
        TransportData $data,
        TransportRelations $relations,
        TransportLinks $links,
        TransportCalls $calls,
        TransportTransactions $transactions,
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
}

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

namespace Katana\Sdk;

use Katana\Sdk\Api\ApiInterface;
use Katana\Sdk\Exception\TransportException;

interface Action extends ApiInterface
{
    /**
     * @return bool
     */
    public function isOrigin(): bool;

    /**
     * @return string
     */
    public function getActionName(): string;

    /**
     * @param string $name
     * @param string $value
     * @return Action
     */
    public function setProperty(string $name, string $value): Action;

    /**
     * @param string $name
     * @return Param
     */
    public function getParam(string $name): Param;

    /**
     * @return Param[]
     */
    public function getParams(): array;

    /**
     * @param string $name
     * @param mixed $value
     * @param string $type
     * @return Param
     */
    public function newParam(
        string $name,
        $value = '',
        string $type = Param::TYPE_STRING
    ): Param;

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile(string $name): bool;

    /**
     * @param string $name
     * @return File
     */
    public function getFile(string $name): File;

    /**
     * @return File[]
     */
    public function getFiles(): array;

    /**
     * @param string $name
     * @param string $path
     * @param string $mime
     * @return File
     */
    public function newFile(
        string $name,
        string $path,
        string $mime = ''
    ): File;

    /**
     * @param File $file
     * @return Action
     */
    public function setDownload(File $file): Action;

    /**
     * @param array $entity
     * @return Action
     * @throws TransportException
     */
    public function setEntity(array $entity): Action;

    /**
     * @param array $collection
     * @return Action
     * @throws TransportException
     */
    public function setCollection(array $collection): Action;

    /**
     * @param string $primaryKey
     * @param string $service
     * @param string $foreignKey
     * @return Action
     */
    public function relateOne(
        string $primaryKey,
        string $service,
        string $foreignKey
    ): Action;

    /**
     * @param string $primaryKey
     * @param string $service
     * @param array $foreignKeys
     * @return Action
     */
    public function relateMany(
        string $primaryKey,
        string $service,
        array $foreignKeys
    ): Action;

    /**
     * @param string $link
     * @param string $uri
     * @return Action
     */
    public function setLink(string $link, string $uri): Action;

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function commit(string $action, array $params = []): Action;

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function rollback(string $action, array $params = []): Action;

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function complete(string $action, array $params = []): Action;

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @param int $timeout
     * @return mixed
     */
    public function call(
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = [],
        int $timeout = 1000
    );

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @return Action
     */
    public function deferCall(
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = []
    ): Action;

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @param int $timeout
     * @return Action
     */
    public function remoteCall(
        string $address,
        string $service,
        string $version,
        string $action,
        array $params = [],
        array $files = [],
        int $timeout = 1000
    ): Action;

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
    ): Action;

    /**
     * @param mixed $value
     * @return Action
     */
    public function setReturn($value): Action;
}

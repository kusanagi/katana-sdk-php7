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

namespace Katana\Sdk;

use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Api\Transport\ServiceData;

/**
 * Interface to the Transport object contained in a payload
 * @package Katana\Sdk
 */
interface Transport
{
    /**
     * Return the uuid of the request
     *
     * @return string
     */
    public function getRequestId(): string;

    /**
     * Return the creation datetime of the request
     *
     * @return string
     */
    public function getRequestTimestamp(): string;

    /**
     * Return the name of the service that was the origin of the request
     *
     * @return array
     */
    public function getOriginService(): array;

    /**
     * Return the execution time in milliseconds that was spent by the origin
     * of the request.
     *
     * @return int
     */
    public function getOriginDuration(): int;

    /**
     * Get a custom userland property
     *
     * The default value will be returned if the property does not exist
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getProperty(string $name, string $default = ''): string;

    /**
     * Get all userland properties as an array
     *
     * @return array
     */
    public function getProperties(): array;

    /**
     * Determine if a file download has been registered for the HTTP response
     *
     * @return boolean
     */
    public function hasDownload(): bool;

    /**
     * Return the file download defined for the HTTP response
     *
     * @return File
     */
    public function getDownload(): File;

    /**
     * Return the data stored in the Transport
     *
     * @return ServiceData[]
     */
    public function getData(): array;

    /**
     * Return all the relations stored in the Transport
     *
     * @return Relation[]
     */
    public function getRelations(): array;

    /**
     * Return all the links stored in the Transport
     *
     * @return Link[]
     */
    public function getLinks(): array;

    /**
     * Return all the calls stored in the Transport
     *
     * If the optional "service" argument is specified, only calls under that
     * service are returned
     *
     * @param string $service
     * @return array
     */
    public function getCalls(string $service = ''): array;

    /**
     * Return all the transactions stored in the Transport
     *
     * If the optional "service" argument is specified, only transactions under
     * that service are returned
     *
     * @param string $service
     * @return array
     */
    public function getTransactions(string $service = ''): array;

    /**
     * Return all the errors stored in the Transport
     *
     * If the optional "service" argument is specified, only errors under
     * that service are returned
     *
     * @param string $service
     * @return array
     */
    public function getErrors(string $service = ''): array;
}

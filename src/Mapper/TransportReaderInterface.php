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

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Transport\Caller;
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

interface TransportReaderInterface
{
    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw);

    /**
     * @param array $raw
     * @return TransportMeta
     */
    public function getTransportMeta(array $raw);

    /**
     * @param array $raw
     * @return TransportFiles
     */
    public function getTransportFiles(array $raw);

    /**
     * @param array $raw
     * @return File|null
     */
    public function getTransportBody(array $raw);

    /**
     * @param array $raw
     * @return ServiceData[]
     */
    public function getTransportData(array $raw): array;

    /**
     * @param array $raw
     * @return Relation[]
     */
    public function getTransportRelations(array $raw): array;

    /**
     * @param array $raw
     * @return Link[]
     */
    public function getTransportLinks(array $raw): array;

    /**
     * @param array $raw
     * @return Caller[]
     */
    public function getTransportCalls(array $raw): array;

    /**
     * @param array $raw
     * @return TransportTransactions
     */
    public function getTransportTransactions(array $raw);

    /**
     * @param array $raw
     * @return TransportErrors
     */
    public function getTransportErrors(array $raw);
}
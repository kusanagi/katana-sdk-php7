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

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Transport\Caller;
use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Api\Transport\ServiceData;
use Katana\Sdk\Api\Transport\Transaction;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;

interface TransportWriterInterface
{
    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw);

    /**
     * @param Transport $transport
     * @return array
     */
    public function writeTransport(Transport $transport);

    /**
     * @param TransportMeta $meta
     * @param array $output
     * @return array
     */
    public function writeTransportMeta(TransportMeta $meta, array $output);

    /**
     * @param TransportFiles $files
     * @param array $output
     * @return array
     */
    public function writeTransportFiles(TransportFiles $files, array $output);

    /**
     * @param File $body
     * @param array $output
     * @return array
     */
    public function writeTransportBody(File $body, array $output);

    /**
     * @param ServiceData[] $data
     * @param array $output
     * @return array
     */
    public function writeTransportData(array $data, array $output): array;

    /**
     * @param Relation[] $relations
     * @param array $output
     * @return array
     */
    public function writeTransportRelations(array $relations, array $output): array;

    /**
     * @param Link[] $links
     * @param array $output
     * @return array
     */
    public function writeTransportLinks(array $links, array $output): array;

    /**
     * @param Caller[] $calls
     * @param array $output
     * @return array
     */
    public function writeTransportCalls(array $calls, array $output): array;

    /**
     * @param Transaction[] $transactions
     * @param array $output
     * @return array
     */
    public function writeTransportTransactions(array $transactions, array $output): array;

    /**
     * @param Transport\Error[] $errors
     * @param array $output
     * @return array
     */
    public function writeTransportErrors(array $errors, array $output): array;
}
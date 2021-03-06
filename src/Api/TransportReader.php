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

namespace Katana\Sdk\Api;

use Katana\Sdk\Api\Transport\Caller;
use Katana\Sdk\Api\Transport\Link;
use Katana\Sdk\Api\Transport\Relation;
use Katana\Sdk\Transport as TransportInterface;
use Katana\Sdk\File as FileInterface;

class TransportReader implements TransportInterface
{
    /**
     * @var Transport
     */
    private $transport;

    /**
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->transport->getMeta()->getId();
    }

    /**
     * @return string
     */
    public function getRequestTimestamp(): string
    {
        return $this->transport->getMeta()->getDatetime();
    }

    /**
     * @return array
     */
    public function getOriginService(): array
    {
        return $this->transport->getMeta()->getOrigin();
    }

    /**
     * @return int
     */
    public function getOriginDuration(): int
    {
        return $this->transport->getMeta()->getDuration();
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getProperty(string $name, string $default = ''): string
    {
        return $this->transport->getMeta()->getProperty($name, $default);
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->transport->getMeta()->getProperties();
    }

    /**
     * @return bool
     */
    public function hasDownload(): bool
    {
        return $this->transport->hasBody();
    }

    /**
     * @return FileInterface
     */
    public function getDownload(): FileInterface
    {
        return $this->transport->getBody();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->transport->getData();
    }

    /**
     * @return Relation[]
     */
    public function getRelations(): array
    {
        return $this->transport->getRelations();
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array {
        return $this->transport->getLinks();
    }

    /**
     * @return Caller[]
     */
    public function getCalls(): array
    {
        return $this->transport->getCalls();
    }

    /**
     * @param string $type
     * @return array
     */
    public function getTransactions(string $type): array
    {
        return $this->transport->getTransactions($type);
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->transport->getErrors();
    }
}

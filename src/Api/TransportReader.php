<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Api;

use Katana\Sdk\Transport as TransportInterface;

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
    public function getRequestId()
    {
        return $this->transport->getMeta()->getId();
    }

    /**
     * @return string
     */
    public function getRequestTimestamp()
    {
        return $this->transport->getMeta()->getDatetime();
    }

    /**
     * @return array
     */
    public function getOrigin()
    {
        return $this->transport->getMeta()->getOrigin();
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getProperty($name, $default = '')
    {
        return $this->transport->getMeta()->getProperty($name) ?: $default;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->transport->getMeta()->getProperties();
    }

    /**
     * @return bool
     */
    public function hasDownload()
    {
        return $this->transport->hasBody();
    }

    /**
     * @return File
     */
    public function getDownload()
    {
        return $this->transport->getBody();
    }

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @return array
     */
    public function getData($address = '', $service = '', $version = '', $action = '')
    {
        return $this->transport->getData()->get($address, $service, $version, $action);
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function getRelations($address = '', $service = '')
    {
        return $this->transport->getRelations()->get($address, $service);
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function getLinks($address = '', $service = '')
    {
        return $this->transport->getLinks()->get($address, $service);
    }

    /**
     * @param string $service
     * @return array
     */
    public function getCalls($service = '')
    {
        return $this->transport->getCalls()->getArray($service);
    }

    /**
     * @param string $service
     * @return array
     */
    public function getTransactions($service = '')
    {
        return $this->transport->getTransactions()->getArray($service);
    }

    /**
     * @param string $address
     * @param string $service
     * @return Error[]
     */
    public function getErrors($address = '', $service = '')
    {
        return $this->transport->getErrors()->getArray($address, $service);
    }
}

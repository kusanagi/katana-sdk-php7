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

namespace Katana\Sdk\Api\Value;

class PayloadMeta
{
    /**
     * @var string
     */
    private $id = '';

    /**
     * @var string
     */
    private $timestamp = '';

    /**
     * @var string
     */
    private $gatewayProtocol = '';

    /**
     * @var string
     */
    private $gatewayAddress = '';

    /**
     * @var string
     */
    private $clientAddress = '';

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @param string $id
     * @param string $timestamp
     * @param string $gatewayProtocol
     * @param string $gatewayAddress
     * @param string $clientAddress
     * @param array $attributes
     */
    public function __construct(
        string $id,
        string $timestamp,
        string $gatewayProtocol,
        string $gatewayAddress,
        string $clientAddress,
        array $attributes
    )
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->gatewayProtocol = $gatewayProtocol;
        $this->gatewayAddress = $gatewayAddress;
        $this->clientAddress = $clientAddress;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getGatewayProtocol(): string
    {
        return $this->gatewayProtocol;
    }

    /**
     * @return string
     */
    public function getGatewayAddress(): string
    {
        return $this->gatewayAddress;
    }

    /**
     * @return string
     */
    public function getClientAddress(): string
    {
        return $this->clientAddress;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getAttribute(string $name, string $default = ''): string
    {
        return $this->attributes[$name] ?? $default;
    }
}
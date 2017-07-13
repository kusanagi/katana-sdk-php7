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

/**
 * Support Transport Api class that encapsulates transport meta data.
 * @package Katana\Sdk\Api
 */
class TransportMeta
{
    /**
     * Version of the platform
     *
     * @var string
     */
    private $version;

    /**
     * Unique id of the request
     *
     * @var string
     */
    private $id;

    /**
     * Datetime of the process in UTC and ISO 8601.
     *
     * @var string
     */
    private $datetime;

    /**
     * The datetime of the start of the current call in UTC and ISO 8601.
     *
     * @var string
     */
    private $startTime;

    /**
     * The datetime of the end of the current call in UTC and ISO 8601.
     *
     * @var string
     */
    private $endTime;

    /**
     * Execution time in milliseconds spent by the origin service.
     *
     * @var int
     */
    private $duration;

    /**
     * The address of the Gateway serving the HTTP request.
     *
     * @var string
     */
    private $gateway;

    /**
     * Origin service for the request
     *
     * @var array
     */
    private $origin = [];

    /**
     * The depth of the service requests during the request
     *
     * MUST begin at 1 and increment with the length of chained calls
     *
     * @var integer
     */
    private $level;

    /**
     * Custom user land properties
     *
     * @var array
     */
    private $properties = [];

    /**
     * @var array
     */
    private $fallbacks = [];

    /**
     * @param string $version
     * @param string $id
     * @param string $datetime
     * @param string $startTime
     * @param string $endTime
     * @param int $duration
     * @param string $gateway
     * @param array $origin
     * @param int $level
     * @param array $properties
     * @param array $fallbacks
     */
    public function __construct(
        $version,
        $id,
        $datetime,
        $startTime,
        $endTime,
        $duration,
        $gateway,
        array $origin,
        $level,
        array $properties = [],
        array $fallbacks = []
    ) {
        $this->version = $version;
        $this->id = $id;
        $this->datetime = $datetime;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->duration = $duration;
        $this->gateway = $gateway;
        $this->origin = $origin;
        $this->level = $level;
        $this->properties = $properties;
        $this->fallbacks = $fallbacks;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * @return string
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @return array
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $name
     * @param string $default
     * @return mixed|string
     */
    public function getProperty(string $name, string $default = ''): string
    {
        return $this->properties[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param $value
     * @return bool
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;

        return true;
    }

    /**
     * @return bool
     */
    public function hasProperties()
    {
        return !empty($this->properties);
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getFallbacks()
    {
        return $this->fallbacks;
    }

    /**
     * @param array $fallbacks
     */
    public function setFallbacks($fallbacks)
    {
        $this->fallbacks = $fallbacks;
    }
}

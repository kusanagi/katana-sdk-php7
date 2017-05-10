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

/**
 * Support Api class that encapsulates a service call
 *
 * @package Katana\Sdk\Api
 */
class DeferCall
{
    /**
     * @var ServiceOrigin
     */
    private $origin;

    /**
     * @var string
     */
    private $caller = '';

    /**
     * @var string
     */
    private $service = '';

    /**
     * @var VersionString
     */
    private $version;

    /**
     * @var string
     */
    private $action;

    /**
     * @var int
     */
    private $duration = 0;

    /**
     * @var Param[]
     */
    private $params = [];

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * Call constructor.
     * @param ServiceOrigin $origin
     * @param string $caller
     * @param string $service
     * @param VersionString $version
     * @param string $action
     * @param int $duration
     * @param Param[] $params
     * @param File[] $files
     */
    public function __construct(
        ServiceOrigin $origin,
        $caller,
        $service,
        VersionString $version,
        $action,
        int $duration,
        array $params = [],
        array $files = []
    ) {
        $this->origin = $origin;
        $this->caller = $caller;
        $this->service = $service;
        $this->version = $version;
        $this->action = $action;
        $this->duration = $duration;
        $this->params = $params;
        $this->files = $files;
    }

    /**
     * @return ServiceOrigin
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getCaller()
    {
        return $this->caller;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version->getVersion();
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}

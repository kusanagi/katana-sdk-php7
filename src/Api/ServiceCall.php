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
 * Support Api class that encapsulates a Service call. *
 *
 * @package Katana\Sdk\Api
 */
class ServiceCall
{
    use ParamContainerTrait;

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
    private $action = '';

    /**
     * @param string $service
     * @param VersionString $version
     * @param string $action
     * @param Param[] $params
     */
    public function __construct(
        $service,
        VersionString $version,
        $action,
        array $params
    ) {
        $this->service = $service;
        $this->version = $version;
        $this->action = $action;
        $this->params = $this->prepareParams($params);
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version->getVersion();
    }

    /**
     * @param VersionString $version
     */
    public function setVersion(VersionString $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
}

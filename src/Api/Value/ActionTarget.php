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

namespace Katana\Sdk\Api\Value;

class ActionTarget
{
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
     */
    public function __construct($service, VersionString $version, $action)
    {
        $this->service = $service;
        $this->version = $version;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return VersionString
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}

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

namespace Katana\Sdk\Schema\Protocol;

/**
 * Defines the Http specific schema for a Service
 *
 * @package Katana\Sdk\Schema\Protocol
 */
class HttpServiceSchema
{
    /**
     * Determines if the Service is accessible to a HTTP request.
     *
     * @var bool
     */
    private $accessible = true;

    /**
     * Defines the base path specified for the Service.
     *
     * @var string
     */
    private $basePath = '';

    /**
     * @param bool $accessible
     * @param string $basePath
     */
    public function __construct($accessible, $basePath)
    {
        $this->accessible = $accessible;
        $this->basePath = $basePath;
    }

    /**
     * @return boolean
     */
    public function isAccessible()
    {
        return $this->accessible;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

}

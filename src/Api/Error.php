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

/**
 * Support Api class that encapsulates an error
 * @package Katana\Sdk\Api
 */
class Error
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $code;

    /**
     * @var string
     */
    private $status;

    /**
     * Error constructor.
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $message
     * @param int $code
     * @param string $status
     */
    public function __construct(
        $address,
        $service,
        $version,
        $message,
        $code = 0,
        $status = ''
    ) {
        $this->address = $address;
        $this->service = $service;
        $this->version = $version;
        $this->message = $message;
        $this->code = $code;
        $this->status = $status ?: '500 Internal Server Error';
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
        return $this->version;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

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

class HttpActionSchema
{
    /**
     * @var bool
     */
    private $accessible = true;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $method = 'get';

    /**
     * @var string
     */
    private $input = 'query';

    /**
     * @var string
     */
    private $body = 'text/plain';

    /**
     * @param bool $accessible
     * @param string $path
     * @param string $method
     * @param string $input
     * @param string $body
     */
    public function __construct($accessible, $path, $method, $input, $body)
    {
        $this->accessible = $accessible;
        $this->path = $path;
        $this->method = $method;
        $this->input = $input;
        $this->body = $body;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}

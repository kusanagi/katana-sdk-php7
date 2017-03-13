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

class HttpFileSchema
{
    /**
     * Determines if the file parameter is writable by a HTTP request.
     *
     * @var bool
     */
    private $accessible = true;

    /**
     * Defines the name of the file parameter as specified via HTTP.
     *
     * @var string
     */
    private $param = '';

    /**
     * @param bool $accessible
     * @param string $param
     */
    public function __construct($accessible, $param)
    {
        $this->accessible = $accessible;
        $this->param = $param;
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
    public function getParam()
    {
        return $this->param;
    }
}

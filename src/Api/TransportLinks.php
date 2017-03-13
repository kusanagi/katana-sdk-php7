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
 * Support Transport Api class that encapsulates a list of links.
 * @package Katana\Sdk\Api
 */
class TransportLinks
{
    /**
     * @var array
     */
    private $links = [];

    /**
     * @param array $links
     */
    public function __construct(array $links = [])
    {
        $this->links = $links;
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function get($address = '', $service = '')
    {
        $links = $this->links;
        if ($address) {
            $links = isset($links[$address])? $links[$address] : [];

            if ($service) {
                $links = isset($links[$service])? $links[$service] : [];
            }
        }

        return $links;
    }

    /**
     * @param string $address
     * @param string $namespace
     * @param string $link
     * @param string $uri
     * @return bool
     */
    public function setLink($address, $namespace, $link, $uri)
    {
        $this->links[$address][$namespace][$link] = $uri;

        return true;
    }
}

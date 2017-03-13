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
 * Support Transport Api class that encapsulates service data.
 *
 * @package Katana\Sdk\Api
 */
class TransportData
{
    /**
     * @var
     */
    private $data = [];

    /**
     * @param $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @return array
     */
    public function get($address = '', $service = '', $version = '', $action = '')
    {
        $data = $this->data;
        if ($address) {
            $data = isset($data[$address])? $data[$address] : [];

            if ($service) {
                $data = isset($data[$service])? $data[$service] : [];

                if ($version) {
                    $data = isset($data[$version])? $data[$version] : [];

                    if (isset($data[$action])) {
                        $data = isset($data[$action])? $data[$action] : [];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @param array $data
     * @return bool
     */
    public function set($address, $service, $version, $action, array $data)
    {
        $this->data[$address][$service][$version][$action][] = $data;

        return true;
    }
}

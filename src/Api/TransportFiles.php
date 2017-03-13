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
 * Support Transport Api class that encapsulates a list of relations.
 * @package Katana\Sdk\Api
 */
class TransportFiles
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @param array $files
     */
    public function __construct(array $files = [])
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->files;
    }

    /**
     * @param string $address
     * @param string $service
     * @param string $version
     * @param string $action
     * @param string $name
     * @return File
     */
    public function get($address, $service, $version, $action, $name)
    {
        return $this->files[$address][$service][$version][$action][$name];
    }

    /**
     * @param string $address
     * @param string $service
     * @param $version
     * @param string $action
     * @param string $name
     * @return bool
     */
    public function has($address, $service, $version, $action, $name)
    {
        return isset($this->files[$address][$service][$version][$action][$name]);
    }

    /**
     * @param string $address
     * @param string $service
     * @param VersionString $version
     * @param string $action
     * @param File $file
     * @return bool
     */
    public function add($address, $service, VersionString $version, $action, File $file)
    {
        $this->files[$address][$service][$version->getVersion()][$action][$file->getName()] = $file;

        return true;
    }
}

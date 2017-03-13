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

namespace Katana\Sdk\Schema;

use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Exception\SchemaException;

class Mapping
{
    /**
     * @var ServiceSchema[]
     */
    private $services = [];

    /**
     * @param ServiceSchema[] $services
     */
    public function load(array $services)
    {
        $this->services = array_values($services);
    }

    /**
     * @param $service
     * @param $version
     * @return ServiceSchema
     * @throws SchemaException
     */
    public function find($service, $version)
    {
        $search = array_filter(
            $this->services,
            function (ServiceSchema $serviceSchema) use ($service, $version) {
                return $serviceSchema->getName() === $service;
            }
        );

        $loadedVersions = array_map(function (ServiceSchema $service) {
            return $service->getVersion();
        }, $search);

        $resolvedVersion = (new VersionString($version))->resolve($loadedVersions);

        if (!$resolvedVersion) {
            throw new SchemaException("Cannot resolve schema for Service: $service ($version)");
        }

        return $search[array_search($resolvedVersion, $loadedVersions)];
    }

    /**
     * @return ServiceSchema[]
     */
    public function getAll()
    {
        return $this->services;
    }
}

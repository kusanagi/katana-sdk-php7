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

namespace Katana\Sdk\Schema;

use Katana\Sdk\Exception\SchemaException;
use Katana\Sdk\Schema\Protocol\HttpServiceSchema;

class ServiceSchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $address = '';

    /**
     * Determines if the Service has a file server configured.
     *
     * @var bool
     */
    private $fileServer = false;

    /**
     * @var HttpServiceSchema
     */
    private $http;

    /**
     * @var ActionSchema[]
     */
    private $actions = [];

    /**
     * @param string $name
     * @param string $version
     * @param string $address
     * @param HttpServiceSchema $http
     * @param ActionSchema[] $actions
     * @param bool $fileServer
     */
    public function __construct(
        $name,
        $version,
        $address,
        HttpServiceSchema $http,
        array $actions,
        $fileServer = false
    ) {
        $actionNames = array_map(function (ActionSchema $action) {
            return $action->getName();
        }, $actions);

        $this->name = $name;
        $this->version = $version;
        $this->address = $address;
        $this->http = $http;
        $this->actions = array_combine($actionNames, $actions);
        $this->fileServer = $fileServer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return bool
     */
    public function hasFileServer()
    {
        return $this->fileServer;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return array_keys($this->actions);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * @param string $name
     * @return ActionSchema
     * @throws SchemaException
     */
    public function getActionSchema($name)
    {
        if (!isset($this->actions[$name])) {
            throw new SchemaException("Cannot resolve schema for action: $name");
        }

        return $this->actions[$name];
    }

    /**
     * @return HttpServiceSchema
     */
    public function getHttpSchema()
    {
        return $this->http;
    }
}

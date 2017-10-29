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
use Katana\Sdk\Component\Component;
use Katana\Sdk\Logger\RequestKatanaLogger;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;

/**
 * Base class for Api classes.
 *
 * @package Katana\Sdk\Api
 */
abstract class Api
{
    use ApiLoggerTrait;

    /**
     * @var RequestKatanaLogger
     */
    protected $logger;

    /**
     * @var Component
     */
    protected $component;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $frameworkVersion;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var Mapping
     */
    protected $mapping;

    /**
     * @param RequestKatanaLogger $logger
     * @param Component $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param array $variables
     * @param bool $debug
     */
    public function __construct(
        RequestKatanaLogger $logger,
        Component $component,
        Mapping $mapping,
        string $path,
        string $name,
        string $version,
        string $frameworkVersion,
        array $variables = [],
        bool $debug = false
    ) {
        $this->logger = $logger;
        $this->component = $component;
        $this->mapping = $mapping;
        $this->path = $path;
        $this->name = $name;
        $this->version = $version;
        $this->frameworkVersion = $frameworkVersion;
        $this->variables = $variables;
        $this->debug = $debug;
    }

    /**
     * @return RequestKatanaLogger
     */
    protected function getLogger(): RequestKatanaLogger
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getFrameworkVersion(): string
    {
        return $this->frameworkVersion;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable(string $name): bool
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getVariable(string $name): string
    {
        if (!isset($this->variables[$name])) {
            return '';
        }

        return $this->variables[$name];
    }

    /**
     * @return boolean
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasResource(string $name): bool
    {
        return $this->component->hasResource($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource(string $name)
    {
        return $this->component->getResource($name);
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return array_map(function (ServiceSchema $service) {
            return [
                'service' => $service->getName(),
                'version' => $service->getVersion(),
            ];
        }, $this->mapping->getAll());
    }

    /**
     * @param string $name
     * @param string $version
     * @return ServiceSchema
     */
    public function getServiceSchema(
        string $name,
        string $version
    ): ServiceSchema
    {
        return $this->mapping->find($name, $version);
    }
}

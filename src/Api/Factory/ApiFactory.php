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

namespace Katana\Sdk\Api\Factory;

use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Mapper\PayloadReaderInterface;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\GlobalKatanaLogger;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Schema\Mapping;

/**
 * Provides methods to get factories for any Api class.
 * @package Katana\Sdk\Api\Factory
 */
abstract class ApiFactory
{
    /**
     * @var Component
     */
    protected $component;

    /**
     * Read mapper to translate an input into Api instances.
     *
     * @var PayloadReaderInterface
     */
    protected $mapper;

    /**
     * @var KatanaLogger
     */
    protected $logger;

    /**
     * @param Component $component
     * @param PayloadReaderInterface $mapper
     * @param KatanaLogger $logger
     * @return ServiceApiFactory
     */
    public static function getServiceFactory(
        Component $component,
        PayloadReaderInterface $mapper,
        KatanaLogger $logger
    ) {
        return new ServiceApiFactory($component, $mapper, $logger);
    }

    /**
     * @param Component $component
     * @param PayloadReaderInterface $mapper
     * @param KatanaLogger $logger
     * @return MiddlewareApiFactory
     */
    public static function getMiddlewareFactory(
        Component $component,
        PayloadReaderInterface $mapper,
        KatanaLogger $logger
    ) {
        return new MiddlewareApiFactory($component, $mapper, $logger);
    }

    /**
     * @param Component $component
     * @param PayloadReaderInterface $mapper
     * @param KatanaLogger $logger
     */
    public function __construct(
        Component $component,
        PayloadReaderInterface $mapper,
        KatanaLogger $logger
    ) {
        $this->component = $component;
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * Build an Api class given a command input.
     *
     * Will use the given read Mapper to translate from the command data to
     * an Api instance.
     *
     * The CliInput provides general information about the component that was
     * defined when the script was executed.
     *
     * @param string $action
     * @param array $command
     * @param CliInput $input
     * @param Mapping $mapping
     * @return Api
     */
    abstract public function build(
        $action,
        array $command,
        CliInput $input,
        Mapping $mapping
    );
}

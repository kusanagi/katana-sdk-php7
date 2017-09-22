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

namespace Katana\Sdk\Console;
use Katana\Sdk\Exception\ConsoleException;

/**
 * Processes cli input
 *
 * @package Katana\Sdk\Console
 */
class CliInput
{
    const MAPPINGS = [
        'compact',
        'extended',
    ];

    /**
     * Type of component
     *
     * @var string
     */
    private $component;

    /**
     * Name of the Component
     *
     * @var string
     */
    private $name;

    /**
     * Version of the component
     *
     * @var string
     */
    private $version;

    /**
     * Version of the Katana platform
     *
     * @var string
     */
    private $frameworkVersion;

    /**
     * Socket name for ZeroMQ to open an IPC socket
     *
     * @var string
     */
    private $socket;

    /**
     * Debug mode
     *
     * @var boolean
     */
    private $debug;

    /**
     * @var string
     */
    private $mapping;

    /**
     * @var string
     */
    private $input = '';

    /**
     * @var string
     */
    private $action = '';

    /**
     * Arbitrary variables as key/value string pairs
     *
     * @var array
     */
    private $variables = [];

    /**
     * @var bool
     */
    private $quiet;

    public static function createFromCli()
    {
        $definition = [
            'component' => new CliOption('c', 'component', CliOption::VALUE_SINGLE),
            'name' => new CliOption('n', 'name', CliOption::VALUE_SINGLE),
            'version' => new CliOption('v', 'version', CliOption::VALUE_SINGLE),
            'framework-version' => new CliOption('p', 'framework-version', CliOption::VALUE_SINGLE),
            'timeout' => new CliOption('T', 'timeout', CliOption::VALUE_SINGLE),
            'socket' => new CliOption('s', 'socket', CliOption::VALUE_SINGLE),
            'debug' => new CliOption('D', 'debug', CliOption::VALUE_NONE),
            'var' => new CliOption('V', 'var', CliOption::VALUE_MULTIPLE),
            'disable-compact-names' => new CliOption('d', 'disable-compact-names', CliOption::VALUE_NONE),
            'action' => new CliOption('a', 'action', CliOption::VALUE_SINGLE),
            'quiet' => new CliOption('q', 'quiet', CliOption::VALUE_NONE),
        ];

        $shortOpts = '';
        $longOpts = [];
        /** @var CliOption $option */
        foreach ($definition as $option) {
            if ($option->getShortDefinition()) {
                $shortOpts .= $option->getShortDefinition();
            }

            if ($option->getLongDefinition()) {
                $longOpts[] = $option->getLongDefinition();
            }
        }

        $options = getopt($shortOpts, $longOpts);

        $optionValues = array_map(function (CliOption $option) use ($options) {
            return $option->parse($options);
        }, $definition);

        return new self(
            $optionValues['component'],
            $optionValues['name'],
            $optionValues['version'],
            $optionValues['framework-version'],
            $optionValues['socket'],
            $optionValues['debug'],
            $optionValues['var'],
            $optionValues['disable-compact-names'] ? 'extended' : 'compact',
            $optionValues['action'],
            $optionValues['quiet']
        );
    }

    /**
     * CliInput constructor.
     * @param string $component
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param string $socket
     * @param bool $debug
     * @param array $variables
     * @param string $mapping
     * @param string $action
     * @param bool $quiet
     * @throws ConsoleException
     */
    public function __construct(
        $component,
        $name,
        $version,
        $frameworkVersion,
        $socket = '',
        $debug = false,
        array $variables = [],
        $mapping = 'compact',
        $action = '',
        $quiet = false
    ) {
        $this->component = $component;
        $this->name = $name;
        $this->version = $version;
        $this->frameworkVersion = $frameworkVersion;
        $socketVersion = preg_replace('/[^a-z0-9]/i', '-', $version);
        $this->socket = $socket ?: "@katana-$component-$name-$socketVersion";
        $this->debug = $debug;
        $this->variables = $variables;
        if (!in_array($mapping, self::MAPPINGS)) {
            throw new ConsoleException("Invalid mapping $mapping");
        }
        $this->mapping = $mapping;
        if ($action) {
            $this->input = file_get_contents('php://stdin');
            $this->action = $action;
        }
        $this->quiet = $quiet;
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
    public function getFrameworkVersion()
    {
        return $this->frameworkVersion;
    }

    /**
     * @return string
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getVariable($name)
    {
        if (!$this->hasVariable($name)) {
            return '';
        }

        return $this->variables['name'];
    }

    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @return bool
     */
    public function hasInput()
    {
        return !empty($this->input);
    }

    /**
     * @return string
     */
    public function getMapping()
    {
        return $this->mapping;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return boolean
     */
    public function isQuiet()
    {
        return $this->quiet;
    }
}

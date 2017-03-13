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

namespace Katana\Sdk\Console;

use Katana\Sdk\Exception\ConsoleException;

/**
 * Defines a cli option
 *
 * @package Katana\Sdk\Console
 */
class CliOption
{
    const VALUE_NONE = 1;
    const VALUE_SINGLE = 2;
    const VALUE_MULTIPLE = 3;

    /**
     * @var string
     */
    private $shortName;

    /**
     * @var string
     */
    private $longName;

    /**
     * @var integer
     */
    private $type;

    /**
     * @param mixed $value
     * @return bool
     * @throws ConsoleException
     */
    private function parseNoValue($value)
    {
        if ($value !== false) {
            throw new ConsoleException('Invalid value');
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws ConsoleException
     */
    private function parseSingleValue($value)
    {
        if ($value === false) {
            throw new ConsoleException('Missing value');
        }

        if (is_array($value)) {
            throw new ConsoleException('Multiple values');
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return array
     * @throws ConsoleException
     */
    private function parseMultipleValue($value)
    {
        if ($value === false) {
            throw new ConsoleException('Missing value');
        }

        $return = [];
        foreach ((array) $value as $varString) {
            if (strpos($varString, '=') === false) {
                throw new ConsoleException('Invalid variable');
            }

            list($name, $val) = explode('=', $varString, 2);
            $return[$name] = $val;
        }

        return $return;
    }

    /**
     * @param string $key
     * @param array $opts
     * @return mixed
     */
    private function parseKey($key, array $opts)
    {
        if (!isset($opts[$key])) {
            return null;
        }

        switch ($this->type) {
            case self::VALUE_NONE:
                return $this->parseNoValue($opts[$key]);
                break;
            case self::VALUE_SINGLE:
                return $this->parseSingleValue($opts[$key]);
                break;
            case self::VALUE_MULTIPLE:
                return $this->parseMultipleValue($opts[$key]);
                break;
        }
    }

    /**
     * @param string $sortName
     * @param string $longName
     * @param int $type
     * @throws ConsoleException
     */
    public function __construct($sortName, $longName, $type)
    {
        if (!is_int($type) || $type < 1 || $type > 3) {
            throw new ConsoleException('Invalid option type');
        }

        $this->shortName = $sortName;
        $this->longName = $longName;
        $this->type = $type;
    }

    /**
     * @param array $opts
     * @return mixed
     * @throws ConsoleException
     */
    public function parse(array $opts)
    {
        $shortValue = $this->parseKey($this->shortName, $opts);
        $longValue = $this->parseKey($this->longName, $opts);

        if ($shortValue !== null && $longValue !== null) {
            if ($this->type !== self::VALUE_MULTIPLE) {
                throw new ConsoleException('Both long and short defined');
            }

            return array_merge($shortValue, $longValue);
        }

        $value = $shortValue ?: $longValue;

        if ($this->type === self::VALUE_NONE && $value === null) {
            return false;
        }

        if ($this->type === self::VALUE_MULTIPLE && $value === null) {
            return [];
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getShortDefinition()
    {
        $definition = $this->shortName;
        if (!$definition) {
            return '';
        }

        if ($this->type !== self::VALUE_NONE) {
            $definition .= ':';
        }

        return $definition;
    }

    /**
     * @return string
     */
    public function getLongDefinition()
    {
        $definition = $this->longName;
        if (!$definition) {
            return '';
        }

        if ($this->type !== self::VALUE_NONE) {
            $definition .= ':';
        }

        return $definition;
    }
}

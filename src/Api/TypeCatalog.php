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

namespace Katana\Sdk\Api;

use Katana\Sdk\Exception\InvalidValueException;

/**
 * Contains the available types in the API.
 * @package Katana\Sdk\Api
 */
class TypeCatalog
{
    const TYPE_NULL    = 'null';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT   = 'float';
    const TYPE_STRING  = 'string';
    const TYPE_ARRAY   = 'array';
    const TYPE_OBJECT  = 'object';
    const TYPE_BINARY  = 'binary';

    /**
     * @param array $value
     * @return bool
     */
    private function isArrayType(array $value): bool
    {
        if ($value === []) {
            return true;
        }
        return array_keys($value) === range(0, count($value) -1);
    }

    /**
     * @param array $value
     * @return bool
     */
    private function isObjectType(array $value): bool
    {
        return count(array_filter(array_keys($value), 'is_string')) === count($value);
    }

    /**
     * Return the default value for a given type.
     *
     * @param string $type
     * @return mixed
     * @throws InvalidValueException
     */
    public function getDefault(string $type)
    {
        switch ($type) {
            case self::TYPE_NULL:
                return null;
            case self::TYPE_BOOLEAN:
                return false;
            case self::TYPE_INTEGER:
            case self::TYPE_FLOAT:
                return 0;
            case self::TYPE_STRING:
            case self::TYPE_BINARY:
                return '';
            case self::TYPE_ARRAY:
            case self::TYPE_OBJECT:
                return [];
        }

        throw new InvalidValueException("Invalid value type: $type");
    }

    /**
     * Validates a value against a type.
     *
     * @param mixed $value
     * @param string $type
     * @return bool
     * @throws InvalidValueException
     */
    public function validate(string $type, $value): bool
    {
        switch ($type) {
            case self::TYPE_NULL:
                return is_null($value);
            case self::TYPE_BOOLEAN:
                return is_bool($value);
            case self::TYPE_INTEGER:
                return is_integer($value);
            case self::TYPE_FLOAT:
                return is_float($value);
            case self::TYPE_STRING:
                return is_string($value);
            case self::TYPE_ARRAY:
                if (!is_array($value)) {
                    return false;
                }
                return $this->isArrayType($value);
            case self::TYPE_OBJECT:
                if (!is_array($value)) {
                    return false;
                }
                return $this->isObjectType($value);
            case self::TYPE_BINARY:
                return is_string($value);
        }

        throw new InvalidValueException("Invalid value type: $type");
    }
}

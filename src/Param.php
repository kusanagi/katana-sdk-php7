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

namespace Katana\Sdk;

interface Param
{
    const TYPE_NULL = 'null';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_STRING = 'string';

    const TYPE_CLASSES = [
        self::TYPE_NULL,
        self::TYPE_BOOLEAN,
        self::TYPE_INTEGER,
        self::TYPE_FLOAT,
        self::TYPE_ARRAY,
        self::TYPE_OBJECT,
        self::TYPE_STRING,
    ];
    
    /**
     * Return the name of the parameter.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return the value of the Param.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Return the type of the Param.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Determine if the Param exists in the request.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Return a copy of the Param with the given name.
     *
     * @param string $name
     * @return Param
     */
    public function copyWithName($name): Param;

    /**
     * Return a copy of the Param with the given value.
     *
     * @param mixed $value
     * @return Param
     */
    public function copyWithValue($value): Param;

    /**
     * Return a copy of the Param with the given type.
     *
     * @param string $type
     * @return Param
     */
    public function copyWithType($type): Param;
}

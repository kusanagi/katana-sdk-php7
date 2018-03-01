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

use Katana\Sdk\Param as ParamInterface;

/**
 * Api class that encapsulates an input Parameter.
 *
 * @package Katana\Sdk\Api
 */
class Param implements ParamInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $exists;

    /**
     * @param mixed $value
     * @param string $type
     * @return array|bool|float|int|null|object
     */
    private function cast($value, string $type)
    {
        switch ($type) {
            case self::TYPE_NULL:
                return null;
            case self::TYPE_BOOLEAN:
                return (bool) $value;
            case self::TYPE_INTEGER:
                return (int) $value;
            case self::TYPE_FLOAT:
                return (float) $value;
            case self::TYPE_ARRAY:
                return (array) $value;
            case self::TYPE_OBJECT:
                return json_decode(json_encode($value));
            case self::TYPE_STRING:
                return $value;
        }

        return $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $type
     * @param bool $exists
     */
    public function __construct(
        string $name,
        $value = '',
        string $type = self::TYPE_STRING,
        bool $exists = false
    ) {
        if (!in_array($type, self::TYPE_CLASSES)) {
            $type = self::TYPE_STRING;
        }

        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->exists = $exists;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->cast($this->value, $this->type);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * @param string $name
     * @return ParamInterface
     */
    public function copyWithName($name): ParamInterface
    {
        return new static(
            $name,
            $this->value,
            $this->type,
            $this->exists
        );
    }

    /**
     * @param string $type
     * @return ParamInterface
     */
    public function copyWithType($type): ParamInterface
    {
        return new static(
            $this->name,
            $this->value,
            $type,
            $this->exists
        );
    }

    /**
     * @param string $value
     * @return ParamInterface
     */
    public function copyWithValue($value): ParamInterface
    {
        return new static(
            $this->name,
            $value,
            $this->type,
            $this->exists
        );
    }
}

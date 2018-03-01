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

namespace Katana\Sdk\Api\Transport;

use Katana\Sdk\Exception\InvalidValueException;

class ActionData
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var bool
     */
    private $collection = false;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $value
     * @return bool
     */
    private function isArrayType(array $value): bool
    {
        if ($value === []) {
            return false;
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
     * @param string $name
     * @param array $data
     * @throws InvalidValueException
     */
    public function __construct(string $name, array $data)
    {
        if ($this->isArrayType($data)) {
            $this->collection = true;
        } elseif (!$this->isObjectType($data)) {
            throw new InvalidValueException("Data with mixed keys received. Invalid entity or collection.");
        }

        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isCollection(): bool
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}

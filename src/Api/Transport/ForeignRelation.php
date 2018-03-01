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

namespace Katana\Sdk\Api\Transport;

use Katana\Sdk\Exception\InvalidValueException;

class ForeignRelation
{
    /**
     * @var string
     */
    private $address = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string[]
     */
    private $foreignKeys = [];

    /**
     * @param string $address
     * @param string $name
     * @param string $type
     * @param string[] $foreignKeys
     * @throws InvalidValueException
     */
    public function __construct(string $address, string $name, string $type, array $foreignKeys)
    {
        if (!in_array($type, ['one', 'many'])) {
            throw new InvalidValueException('Invalid relation type. Valid types are "one" and "many"');
        }

        $this->address = $address;
        $this->name = $name;
        $this->type = $type;
        $this->foreignKeys = $foreignKeys;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
}

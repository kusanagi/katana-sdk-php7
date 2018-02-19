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

class Relation
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
    private $primaryKey = '';

    /**
     * @var ForeignRelation[]
     */
    private $foreignRelations = [];

    /**
     * @param string $address
     * @param string $name
     * @param string $primaryKey
     * @param ForeignRelation[] $foreignRelations
     */
    public function __construct(string $address, string $name, string $primaryKey, array $foreignRelations)
    {
        $this->address = $address;
        $this->name = $name;
        $this->primaryKey = $primaryKey;
        $this->foreignRelations = $foreignRelations;
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
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return ForeignRelation[]
     */
    public function getForeignRelations(): array
    {
        return $this->foreignRelations;
    }
}

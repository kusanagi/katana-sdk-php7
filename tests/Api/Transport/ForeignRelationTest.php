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

namespace Katana\Sdk\Tests\Api\Transport;

use Katana\Sdk\Api\Transport\ForeignRelation;
use Katana\Sdk\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class ForeignRelationTest extends TestCase
{
    public function testInvalidForeignRelation()
    {
        $this->expectException(InvalidValueException::class);
        $foreignRelation = new ForeignRelation(
            'address',
            'name',
            'type',
            ['one']
        );

        $this->assertEquals('address', $foreignRelation->getAddress());
        $this->assertEquals('name', $foreignRelation->getName());
        $this->assertEquals('type', $foreignRelation->getType());
        $this->assertEquals(['one', 'two'], $foreignRelation->getForeignKeys());
    }

    public function testForeignSimpleRelation()
    {
        $foreignRelation = new ForeignRelation(
            'address',
            'name',
            'one',
            ['one']
        );

        $this->assertEquals('address', $foreignRelation->getAddress());
        $this->assertEquals('name', $foreignRelation->getName());
        $this->assertEquals('one', $foreignRelation->getType());
        $this->assertEquals(['one'], $foreignRelation->getForeignKeys());
    }

    public function testForeignMultipleRelation()
    {
        $foreignRelation = new ForeignRelation(
            'address',
            'name',
            'many',
            ['one', 'two']
        );

        $this->assertEquals('address', $foreignRelation->getAddress());
        $this->assertEquals('name', $foreignRelation->getName());
        $this->assertEquals('many', $foreignRelation->getType());
        $this->assertEquals(['one', 'two'], $foreignRelation->getForeignKeys());
    }
}

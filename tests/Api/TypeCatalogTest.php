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

namespace Katana\Sdk\Tests\Api;

use Katana\Sdk\Api\TypeCatalog;
use PHPUnit\Framework\TestCase;

class TypeCatalogTest extends TestCase
{
    /**
     * @var TypeCatalog
     */
    private $typeCatalog;

    public function setUp()
    {
        $this->typeCatalog = new TypeCatalog();
    }

    public function testNullDefault()
    {
        $this->assertNull(
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_NULL)
        );
    }

    public function testBooleanDefault()
    {
        $this->assertFalse(
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_BOOLEAN)
        );
    }

    public function testIntegerDefault()
    {
        $this->assertEquals(
            0,
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_INTEGER)
        );
    }

    public function testFloatDefault()
    {
        $this->assertEquals(
            0,
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_FLOAT)
        );
    }

    public function testStringDefault()
    {
        $this->assertEquals(
            '',
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_STRING)
        );
    }

    public function testArrayDefault()
    {
        $this->assertEquals(
            [],
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_ARRAY)
        );
    }

    public function testObjectDefault()
    {
        $this->assertEquals(
            [],
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_OBJECT)
        );
    }

    public function testBinaryDefault()
    {
        $this->assertEquals(
            '',
            $this->typeCatalog->getDefault(TypeCatalog::TYPE_BINARY)
        );
    }

    public function validateDataProvider()
    {
        return [
            // Type Null
            [true, TypeCatalog::TYPE_NULL, null],
            [false, TypeCatalog::TYPE_NULL, false],
            [false, TypeCatalog::TYPE_NULL, true],
            [false, TypeCatalog::TYPE_NULL, 0],
            [false, TypeCatalog::TYPE_NULL, 42],
            [false, TypeCatalog::TYPE_NULL, 0.0],
            [false, TypeCatalog::TYPE_NULL, 3.1416],
            [false, TypeCatalog::TYPE_NULL, ''],
            [false, TypeCatalog::TYPE_NULL, 'foo'],
            [false, TypeCatalog::TYPE_NULL, "\x01\x02"],
            [false, TypeCatalog::TYPE_NULL, []],
            [false, TypeCatalog::TYPE_NULL, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_NULL, ['a' => 1, 'b' => 2]],
            // Type Boolean
            [false, TypeCatalog::TYPE_BOOLEAN, null],
            [true, TypeCatalog::TYPE_BOOLEAN, false],
            [true, TypeCatalog::TYPE_BOOLEAN, true],
            [false, TypeCatalog::TYPE_BOOLEAN, 0],
            [false, TypeCatalog::TYPE_BOOLEAN, 42],
            [false, TypeCatalog::TYPE_BOOLEAN, 0.0],
            [false, TypeCatalog::TYPE_BOOLEAN, 3.1416],
            [false, TypeCatalog::TYPE_BOOLEAN, ''],
            [false, TypeCatalog::TYPE_BOOLEAN, 'foo'],
            [false, TypeCatalog::TYPE_BOOLEAN, "\x01\x02"],
            [false, TypeCatalog::TYPE_BOOLEAN, []],
            [false, TypeCatalog::TYPE_BOOLEAN, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_BOOLEAN, ['a' => 1, 'b' => 2]],
            // Type Integer
            [false, TypeCatalog::TYPE_INTEGER, null],
            [false, TypeCatalog::TYPE_INTEGER, false],
            [false, TypeCatalog::TYPE_INTEGER, true],
            [true, TypeCatalog::TYPE_INTEGER, 0],
            [true, TypeCatalog::TYPE_INTEGER, 42],
            [false, TypeCatalog::TYPE_INTEGER, 0.0],
            [false, TypeCatalog::TYPE_INTEGER, 3.1416],
            [false, TypeCatalog::TYPE_INTEGER, ''],
            [false, TypeCatalog::TYPE_INTEGER, 'foo'],
            [false, TypeCatalog::TYPE_INTEGER, "\x01\x02"],
            [false, TypeCatalog::TYPE_INTEGER, []],
            [false, TypeCatalog::TYPE_INTEGER, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_INTEGER, ['a' => 1, 'b' => 2]],
            // Type Float
            [false, TypeCatalog::TYPE_FLOAT, null],
            [false, TypeCatalog::TYPE_FLOAT, false],
            [false, TypeCatalog::TYPE_FLOAT, true],
            [false, TypeCatalog::TYPE_FLOAT, 0],
            [false, TypeCatalog::TYPE_FLOAT, 42],
            [true, TypeCatalog::TYPE_FLOAT, 0.0],
            [true, TypeCatalog::TYPE_FLOAT, 3.1416],
            [false, TypeCatalog::TYPE_FLOAT, ''],
            [false, TypeCatalog::TYPE_FLOAT, 'foo'],
            [false, TypeCatalog::TYPE_FLOAT, "\x01\x02"],
            [false, TypeCatalog::TYPE_FLOAT, []],
            [false, TypeCatalog::TYPE_FLOAT, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_FLOAT, ['a' => 1, 'b' => 2]],
            // Type String
            [false, TypeCatalog::TYPE_STRING, null],
            [false, TypeCatalog::TYPE_STRING, false],
            [false, TypeCatalog::TYPE_STRING, true],
            [false, TypeCatalog::TYPE_STRING, 0],
            [false, TypeCatalog::TYPE_STRING, 42],
            [false, TypeCatalog::TYPE_STRING, 0.0],
            [false, TypeCatalog::TYPE_STRING, 3.1416],
            [true, TypeCatalog::TYPE_STRING, ''],
            [true, TypeCatalog::TYPE_STRING, 'foo'],
            [true, TypeCatalog::TYPE_STRING, "\x01\x02"],
            [false, TypeCatalog::TYPE_STRING, []],
            [false, TypeCatalog::TYPE_STRING, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_STRING, ['a' => 1, 'b' => 2]],
            // Type Array
            [false, TypeCatalog::TYPE_ARRAY, null],
            [false, TypeCatalog::TYPE_ARRAY, false],
            [false, TypeCatalog::TYPE_ARRAY, true],
            [false, TypeCatalog::TYPE_ARRAY, 0],
            [false, TypeCatalog::TYPE_ARRAY, 42],
            [false, TypeCatalog::TYPE_ARRAY, 0.0],
            [false, TypeCatalog::TYPE_ARRAY, 3.1416],
            [false, TypeCatalog::TYPE_ARRAY, ''],
            [false, TypeCatalog::TYPE_ARRAY, 'foo'],
            [false, TypeCatalog::TYPE_ARRAY, "\x01\x02"],
            [true, TypeCatalog::TYPE_ARRAY, []],
            [true, TypeCatalog::TYPE_ARRAY, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_ARRAY, ['a' => 1, 'b' => 2]],
            // Type Object
            [false, TypeCatalog::TYPE_OBJECT, null],
            [false, TypeCatalog::TYPE_OBJECT, false],
            [false, TypeCatalog::TYPE_OBJECT, true],
            [false, TypeCatalog::TYPE_OBJECT, 0],
            [false, TypeCatalog::TYPE_OBJECT, 42],
            [false, TypeCatalog::TYPE_OBJECT, 0.0],
            [false, TypeCatalog::TYPE_OBJECT, 3.1416],
            [false, TypeCatalog::TYPE_OBJECT, ''],
            [false, TypeCatalog::TYPE_OBJECT, 'foo'],
            [false, TypeCatalog::TYPE_OBJECT, "\x01\x02"],
            [true, TypeCatalog::TYPE_OBJECT, []],
            [false, TypeCatalog::TYPE_OBJECT, ['a', 'b', 'c']],
            [true, TypeCatalog::TYPE_OBJECT, ['a' => 1, 'b' => 2]],
            // Type Binary
            [false, TypeCatalog::TYPE_BINARY, null],
            [false, TypeCatalog::TYPE_BINARY, false],
            [false, TypeCatalog::TYPE_BINARY, true],
            [false, TypeCatalog::TYPE_BINARY, 0],
            [false, TypeCatalog::TYPE_BINARY, 42],
            [false, TypeCatalog::TYPE_BINARY, 0.0],
            [false, TypeCatalog::TYPE_BINARY, 3.1416],
            [true, TypeCatalog::TYPE_BINARY, ''],
            [true, TypeCatalog::TYPE_BINARY, 'foo'],
            [true, TypeCatalog::TYPE_BINARY, "\x01\x02"],
            [false, TypeCatalog::TYPE_BINARY, []],
            [false, TypeCatalog::TYPE_BINARY, ['a', 'b', 'c']],
            [false, TypeCatalog::TYPE_BINARY, ['a' => 1, 'b' => 2]],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidateNullType(bool $expected, string $type, $value)
    {
        $this->assertEquals(
            $expected,
            $this->typeCatalog->validate($type, $value)
        );
    }
}

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

namespace Katana\Sdk\Tests\Api\Value;

use Katana\Sdk\Api\Value\ReturnValue;
use Katana\Sdk\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class ReturnValueTest extends TestCase
{
    public function testEmptyReturn()
    {
        $return = new ReturnValue();

        $this->assertFalse($return->exists());
        $this->expectException(InvalidValueException::class);
        $return->getValue();
    }

    public function testReturnNull()
    {
        $return = new ReturnValue(null, true);

        $this->assertTrue($return->exists());
        $this->assertNull($return->getValue());
    }

    public function testReturnValue()
    {
        $return = new ReturnValue(5);

        $this->assertTrue($return->exists());
        $this->assertEquals(5, $return->getValue());
    }
}

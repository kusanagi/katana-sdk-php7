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

use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class VersionStringTest extends TestCase
{
    public function testInvalidString()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Invalid version: 0.1.0#hash');
        new VersionString('0.1.0#hash');
    }

    public function testValidString()
    {
        $version = new VersionString('0.1.0-rc.2');
        $this->assertEquals('0.1.0-rc.2', $version->getVersion());
        $this->assertTrue($version->match('0.1.0-rc.2'));
        $this->assertFalse($version->match('0.1.0-rc.1'));
    }

    public function testMatchPattern()
    {
        $version = new VersionString('0.1.*-rc.2');
        $this->assertTrue($version->match('0.1.0-rc.2'));
        $this->assertTrue($version->match('0.1.9-rc.2'));
        $this->assertTrue($version->match('0.1.a-rc.2'));
        $this->assertTrue($version->match('0.1.5a-rc.2'));
        $this->assertFalse($version->match('0.1.0--rc.1'));
    }

    public function testMatchBeginningWithWildcard()
    {
        $version = new VersionString('*.1.0');
        $this->assertTrue($version->match('0.1.0'));
        $this->assertTrue($version->match('1.1.0'));
        $this->assertTrue($version->match('12a.1.0'));
        $this->assertFalse($version->match('0.1.5a-rc.2'));
        $this->assertFalse($version->match('0.1.0--rc.1'));
    }

    public function testMatchEndingWithWildcard()
    {
        $version = new VersionString('1.1.*');
        $this->assertTrue($version->match('1.1.0'));
        $this->assertTrue($version->match('1.1.5'));
        $this->assertTrue($version->match('1.1.32b'));
        $this->assertTrue($version->match('1.1.0-rc.1'));
        $this->assertFalse($version->match('0.1.5a'));
    }

    public function testAdjacentWildcards()
    {
        $version = new VersionString('1.***.0');
        $this->assertTrue($version->match('1.1.0'));
        $this->assertTrue($version->match('1.5b.0'));
        $this->assertTrue($version->match('1.1,3_dev.0'));
        $this->assertFalse($version->match('0.1.0'));
        $this->assertFalse($version->match('1.1.1.0'));
    }

    public function testMultipleWildcards()
    {
        $version = new VersionString('1.0.*-beta.*');
        $this->assertTrue($version->match('1.0.0-beta.1'));
        $this->assertTrue($version->match('1.0.5b-beta.55-build44'));
        $this->assertTrue($version->match('1.0.1,3_dev-beta.0'));
        $this->assertFalse($version->match('1.0.3'));
        $this->assertFalse($version->match('1.0.1-beta.'));
    }

    public function testResolveOne()
    {
        $version = new VersionString('1.1.*');
        $this->assertEquals('1.1.1', $version->resolve([
            '1.0.0',
            '1.1.1',
            '0.1.1',
        ]));
    }

    public function testResolveNone()
    {
        $version = new VersionString('1.1.*');
        $solved = $version->resolve([
            '1.0.0',
            '1.2.1',
            '0.1.1',
        ]);
        $this->assertInternalType('string', $solved);
        $this->assertEquals('', $solved);
    }

    /**
     * @dataProvider versionProvider
     * @param $pattern
     * @param $expected
     * @param $candidates
     */
    public function testResolvePrecedence($pattern, $expected, $candidates)
    {
        $version = new VersionString($pattern);
        $solved = $version->resolve($candidates);
        $this->assertInternalType('string', $solved);
        $this->assertEquals($expected, $solved);
    }

    public function versionProvider()
    {
        return [
            ['3.4.*', '3.4.1', ['3.4.0', '3.4.1', '3.4.a',]],
            ['3.4.*', '3.4.alpha', ['3.4.alpha', '3.4.beta', '3.4.gamma',]],
            ['3.4.*', '3.4.a', ['3.4.alpha', '3.4.a', '3.4.gamma',]],
            ['3.4.*', '3.4.1', ['3.4.a', '3.4.1', '3.4.0',]],
            ['3.4.*', '3.4.12', ['3.4.a', '3.4.12', '3.4.1',]],
            ['3.4.*', '3.4.0', ['3.4.0', '3.4.0-a', '3.4.0-0',]],
            ['3.4.*', '3.4.0-1', ['3.4.0-0', '3.4.0-a', '3.4.0-1',]],
            ['3.4.*', '3.4.0-1', ['3.4.0-0', '3.4.0-1-0', '3.4.0-1',]],
        ];
    }
}

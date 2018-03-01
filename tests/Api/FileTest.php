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

namespace Katana\Sdk\Tests\Api;

use Katana\Sdk\Api\File;
use Katana\Sdk\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testEmptyPath()
    {
        $file = new File('file');
        $this->assertEquals('file', $file->getName());
        $this->assertEquals('', $file->getPath());
        $this->assertEquals('', $file->getMime());
        $this->assertEquals(0, $file->getSize());
        $this->assertEquals('', $file->getFilename());
        $this->assertEquals('', $file->getToken());
        $this->assertFalse($file->exists());
    }

    public function testHttpFileMissingMime()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Missing mime for File: file');
        new File('file', 'http://localhost/file.txt');
    }

    public function testHttpFileMissingFilename()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Missing filename for File: file');
        new File('file', 'http://localhost/file.txt', 'text/plain');
    }

    public function testHttpFileMissingSize()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Missing size for File: file');
        new File('file', 'http://localhost/file.txt', 'text/plain', 'file.txt');
    }

    public function testHttpFileMissingToken()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Missing token for File: file');
        new File('file', 'http://localhost/file.txt', 'text/plain', 'file.txt', 256);
    }

    public function testLocalFileNotFound()
    {
        $path = __DIR__ . '/file_not_found';
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage("File does not exist in path: $path");
        new File('file', "file://$path", 'text/plain', 'file.txt', 256);
    }

    public function testLocalFile()
    {
        $path = __DIR__ . '/file.txt';
        $file = new File('file', "file://$path");
        $this->assertEquals('file', $file->getName());
        $this->assertEquals("file://$path", $file->getPath());
        $this->assertEquals('text/plain', $file->getMime());
        $this->assertEquals(11, $file->getSize());
        $this->assertEquals('file.txt', $file->getFilename());
        $this->assertEquals('', $file->getToken());
        $this->assertTrue($file->exists());
    }

    public function testLocalFileOverrideValues()
    {
        $path = __DIR__ . '/file.txt';
        $file = new File('file', "file://$path", 'application/json', 'file.json', 116);
        $this->assertEquals('file', $file->getName());
        $this->assertEquals("file://$path", $file->getPath());
        $this->assertEquals('application/json', $file->getMime());
        $this->assertEquals(116, $file->getSize());
        $this->assertEquals('file.json', $file->getFilename());
        $this->assertEquals('', $file->getToken());
        $this->assertTrue($file->exists());
    }

    public function testFileWithNoPrefixNotFound()
    {
        $path = __DIR__ . '/file_not_found';
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage("File does not exist in path: $path");
        new File('file', $path, 'text/plain', 'file.txt', 256);
    }

    public function testLocalFileWithNoPrefix()
    {
        $path = __DIR__ . '/file.txt';
        $file = new File('file', $path);
        $this->assertEquals('file', $file->getName());
        $this->assertEquals("file://$path", $file->getPath());
        $this->assertEquals('text/plain', $file->getMime());
        $this->assertEquals(11, $file->getSize());
        $this->assertEquals('file.txt', $file->getFilename());
        $this->assertEquals('', $file->getToken());
        $this->assertTrue($file->exists());
    }

    public function testLocalFileWithToken()
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('Unexpected token for File: file');
        new File('file', 'file://' . __FILE__, 'text/plain', 'file.txt', 256, 'token');
    }

    public function testLocalRelativeFile()
    {
        chdir(__DIR__);
        $path = './file.txt';
        $expectedPath = __DIR__ . '/file.txt';
        $file = new File('file', "file://$path");
        $this->assertEquals('file', $file->getName());
        $this->assertEquals("file://$expectedPath", $file->getPath());
        $this->assertEquals('text/plain', $file->getMime());
        $this->assertEquals(11, $file->getSize());
        $this->assertEquals('file.txt', $file->getFilename());
        $this->assertEquals('', $file->getToken());
        $this->assertTrue($file->exists());
    }
}

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

namespace Katana\Sdk\Tests\Api\Transport;

use Katana\Sdk\Api\Transport\ActionData;
use Katana\Sdk\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

class ActionDataTest extends TestCase
{
    public function testInvalidData()
    {
        $this->expectException(InvalidValueException::class);
        new ActionData('test', [
            'one',
            'key' => 'two',
            'three',
        ]);
    }

    public function testEmptyEntity()
    {
        $data = [];
        $actionData = new ActionData('test', $data);

        $this->assertFalse($actionData->isCollection());
        $this->assertEquals('test', $actionData->getName());
        $this->assertEquals($data, $actionData->getData());
    }

    public function testEntity()
    {
        $data = [
            'one' => 'foo',
            'two' => 'bar',
        ];
        $actionData = new ActionData('test', $data);

        $this->assertFalse($actionData->isCollection());
        $this->assertEquals('test', $actionData->getName());
        $this->assertEquals($data, $actionData->getData());
    }

    public function testCollection()
    {
        $data = [
            [
                'one' => 'foo',
                'two' => 'bar',
            ],
            [
                'one' => 'foo2',
                'two' => 'bar2',
            ],
        ];
        $actionData = new ActionData('test', $data);

        $this->assertTrue($actionData->isCollection());
        $this->assertEquals('test', $actionData->getName());
        $this->assertEquals($data, $actionData->getData());
    }
}

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

namespace Katana\Sdk\Tests\Schema;

use Katana\Sdk\Schema\ActionEntity;
use Katana\Sdk\Schema\ActionSchema;
use Katana\Sdk\Schema\Protocol\HttpActionSchema;
use PHPUnit\Framework\TestCase;

class ActionSchemaTest extends TestCase
{
    public function testHasCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [
                ['service', 'version', 'action']
            ],
            [],
            []
        );

        $this->assertTrue(
            $actionSchema->hasCall('service', 'version', 'action')
        );
    }

    public function testHasWilcardCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [
                ['*', '*', '*']
            ],
            [],
            []
        );

        $this->assertTrue(
            $actionSchema->hasCall('service', 'version', 'action')
        );
    }

    public function testHasNotCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [],
            []
        );

        $this->assertFalse(
            $actionSchema->hasCall('service', 'version', 'action')
        );
    }

    public function testHasDeferCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [
                ['service', 'version', 'action']
            ],
            []
        );

        $this->assertTrue(
            $actionSchema->hasDeferCall('service', 'version', 'action')
        );
    }

    public function testHasWilcardDeferCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [
                ['*', '*', '*']
            ],
            []
        );

        $this->assertTrue(
            $actionSchema->hasDeferCall('service', 'version', 'action')
        );
    }

    public function testHasNotDeferCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [],
            []
        );

        $this->assertFalse(
            $actionSchema->hasDeferCall('service', 'version', 'action')
        );
    }

    public function testHasRemoteCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [],
            [
                ['127.0.0.1:1234', 'service', 'version', 'action']
            ]
        );

        $this->assertTrue(
            $actionSchema->hasRemoteCall('127.0.0.1:1234', 'service', 'version', 'action')
        );
    }

    public function testHasWilcardRemoteCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [],
            [
                ['*', '*', '*', '*']
            ]
        );

        $this->assertTrue(
            $actionSchema->hasRemoteCall('127.0.0.1:1234', 'service', 'version', 'action')
        );
    }

    public function testHasNotRemoteCall()
    {
        $actionSchema = new ActionSchema(
            'test',
            $this->prophesize(ActionEntity::class)->reveal(),
            $this->prophesize(HttpActionSchema::class)->reveal(),
            false,
            [],
            [],
            [],
            [],
            [],
            []
        );

        $this->assertFalse(
            $actionSchema->hasRemoteCall('127.0.0.1:1234', 'service', 'version', 'action')
        );
    }
}

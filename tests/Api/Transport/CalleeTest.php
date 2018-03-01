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

use Katana\Sdk\Api\Transport\Callee;
use Katana\Sdk\Param;
use PHPUnit\Framework\TestCase;

class CalleeTest extends TestCase
{
    public function testCallee()
    {
        $callee = new Callee(
            1000,
            42,
            'address',
            'name',
            'version',
            'action',
            [
                $this->prophesize(Param::class)->reveal(),
                $this->prophesize(Param::class)->reveal(),
            ]
        );

        $this->assertEquals(1000, $callee->getTimeout());
        $this->assertEquals(42, $callee->getDuration());
        $this->assertTrue($callee->isRemote());
        $this->assertEquals('address', $callee->getAddress());
        $this->assertEquals('name', $callee->getName());
        $this->assertEquals('version', $callee->getVersion());
        $this->assertEquals('action', $callee->getAction());
        $this->assertCount(2, $callee->getParams());
        $this->assertContainsOnlyInstancesOf(Param::class, $callee->getParams());
    }
}

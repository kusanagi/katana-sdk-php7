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
use Katana\Sdk\Api\Transport\Caller;
use PHPUnit\Framework\TestCase;

class CallerTest extends TestCase
{
    public function testCaller()
    {
        $caller = new Caller(
            'name',
            'version',
            'action',
            $this->prophesize(Callee::class)->reveal()
        );

        $this->assertEquals('name', $caller->getName());
        $this->assertEquals('version', $caller->getVersion());
        $this->assertEquals('action', $caller->getAction());
        $this->assertInstanceOf(Callee::class, $caller->getCallee());
    }
}

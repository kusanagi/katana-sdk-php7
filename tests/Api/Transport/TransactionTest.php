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

use Katana\Sdk\Api\Transport\Transaction;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Param;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testInvalidTransaction()
    {
        $this->expectException(InvalidValueException::class);
        new Transaction(
            'wrong',
            'name',
            'version',
            'caller action',
            'callee action',
            [
                $this->prophesize(Param::class)->reveal(),
                $this->prophesize(Param::class)->reveal(),
            ]
        );
    }

    public function testTransaction()
    {
        $transaction = new Transaction(
            'commit',
            'name',
            'version',
            'caller action',
            'callee action',
            [
                $this->prophesize(Param::class)->reveal(),
                $this->prophesize(Param::class)->reveal(),
            ]
        );

        $this->assertEquals('commit', $transaction->getType());
        $this->assertEquals('name', $transaction->getName());
        $this->assertEquals('version', $transaction->getVersion());
        $this->assertEquals('caller action', $transaction->getCallerAction());
        $this->assertEquals('callee action', $transaction->getCalleeAction());
        $this->assertCount(2, $transaction->getParams());
        $this->assertContainsOnlyInstancesOf(Param::class, $transaction->getParams());
    }
}

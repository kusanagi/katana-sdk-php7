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

use Katana\Sdk\Api\Transport\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testError()
    {
        $error = new Error(
            'address',
            'name',
            'version',
            'message',
            1,
            'status'
        );

        $this->assertEquals('address', $error->getAddress());
        $this->assertEquals('name', $error->getName());
        $this->assertEquals('version', $error->getVersion());
        $this->assertEquals('message', $error->getMessage());
        $this->assertEquals(1, $error->getCode());
        $this->assertEquals('status', $error->getStatus());
    }
}

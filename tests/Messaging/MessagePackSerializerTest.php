<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Tests\Schema;

use Katana\Sdk\Messaging\MessagePackSerializer;
use PHPUnit\Framework\TestCase;

class MessagePackSerializerTest extends TestCase
{
    /**
     * @requires extension msgpack
     */
    public function testSerialize()
    {
        $msg = 'test';

        $serializer = new MessagePackSerializer();

        $this->assertEquals(msgpack_pack($msg), $serializer->serialize($msg));
    }

    /**
     * @requires extension msgpack
     */
    public function testUnserialize()
    {
        $msg = 'test';

        $serializer = new MessagePackSerializer();

        $this->assertEquals($msg, $serializer->unserialize(msgpack_pack($msg)));
    }
}

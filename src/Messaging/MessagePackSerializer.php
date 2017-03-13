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

namespace Katana\Sdk\Messaging;

use MessagePack\BufferUnpacker;

class MessagePackSerializer
{
    /**
     * @param $message
     * @return mixed
     * @throws \Exception
     */
    public function serialize($message)
    {
        if (!function_exists('msgpack_pack')) {
            throw new \Exception('Message pack extension not found');
        }

        return msgpack_pack($message);
    }

    /**
     * @param $message
     * @return mixed
     * @throws \Exception
     */
    public function unserialize($message)
    {
        if (!function_exists('msgpack_unpack')) {
            throw new \Exception('Message pack extension not found');
        }

        // msgpack version lower than 2.* result in segfault on unpack
        $msgpackVersion = phpversion("msgpack");
        if (explode('.', $msgpackVersion)[0] < 2) {
            // Fallback msgpack library
            $unpacker = new BufferUnpacker();
            $unpacker->reset($message);

            return $unpacker->unpack();
        }

        return msgpack_unpack($message);
    }
}

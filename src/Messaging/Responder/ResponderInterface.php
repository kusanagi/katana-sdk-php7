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

namespace Katana\Sdk\Messaging\Responder;

use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;

/**
 * Interface to classes that take an Api and send a response
 *
 * @package Katana\Sdk\Messaging\Responder
 */
interface ResponderInterface
{
    /**
     * @param Api $api
     * @param PayloadWriterInterface $mapper
     */
    public function sendResponse(Api $api, PayloadWriterInterface $mapper);

    /**
     * @param PayloadWriterInterface $mapper
     * @param string $message
     * @param int $code
     * @param string $status
     * @return
     */
    public function sendErrorResponse(
        PayloadWriterInterface $mapper,
        $message = '',
        $code = 0,
        $status = ''
    );
}

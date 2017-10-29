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

namespace Katana\Sdk\Logger;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
class GlobalKatanaLogger extends KatanaLogger
{
    /**
     * @param int $level
     * @param string $message
     * @return string
     */
    protected function formatMessage(int $level, string $message): string
    {
        return trim(str_replace(
            ['%TIMESTAMP%', '%TYPE%', '%MESSAGE%'],
            [$this->getTimestamp(), self::LOG_LEVELS[$level], $message],
            '%TIMESTAMP% [%TYPE%] [SDK] %MESSAGE%'
        ));
    }

    /**
     * @param string $requestId
     * @return RequestKatanaLogger
     */
    public function getRequestLogger(string $requestId): RequestKatanaLogger
    {
        return new RequestKatanaLogger($requestId, $this->getLevel());
    }
}

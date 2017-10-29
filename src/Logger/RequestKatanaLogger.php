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
class RequestKatanaLogger extends KatanaLogger
{
    /**
     * @var string
     */
    private $requestId = '';

    /**
     * @param string $requestId
     * @param int|null $level
     */
    public function __construct(string $requestId, int $level = null)
    {
        $this->requestId = $requestId;
        parent::__construct($level);
    }

    /**
     * @param int $level
     * @param string $message
     * @return string
     */
    protected function formatMessage(int $level, string $message): string
    {
        return trim(str_replace(
            ['%TIMESTAMP%', '%TYPE%', '%MESSAGE%', '%REQUEST_ID%'],
            [$this->getTimestamp(), self::LOG_LEVELS[$level], $message, $this->requestId],
            '%TIMESTAMP% [%TYPE%] [SDK] %MESSAGE% |%REQUEST_ID%|'
        ));
    }
}

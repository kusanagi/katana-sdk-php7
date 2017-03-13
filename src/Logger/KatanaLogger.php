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

namespace Katana\Sdk\Logger;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
class KatanaLogger
{
    const FORMAT = '%TIMESTAMP% [%TYPE%] [SDK] %MESSAGE% %REQUEST_ID%';

    const LOG_DEBUG = 0;
    const LOG_INFO = 1;
    const LOG_WARNING = 2;
    const LOG_ERROR = 3;
    const LOG_NONE = 4;

    const LOG_LEVELS = [
        self::LOG_DEBUG => 'DEBUG',
        self::LOG_INFO => 'INFO',
        self::LOG_WARNING => 'WARNING',
        self::LOG_ERROR => 'ERROR',
        self::LOG_NONE => 'NONE',
    ];

    /**
     * @var int
     */
    private $level = self::LOG_INFO;

    /**
     * @param int $level
     */
    public function __construct($level = null)
    {
        if ($level !== null) {
            $this->level = $level;
        }
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return bool|string
     */
    private function getTimestamp()
    {
        list($usec, $sec) = explode(" ", microtime());
        return sprintf(
            "%s.%dZ",
            (new \DateTime("@$sec"))->format('Y-m-d\TH:i:s'),
            round($usec * 1000)
        );
    }

    /**
     * @param string $level
     * @param string $message
     * @param string $requestId
     */
    private function log($level, $message, $requestId = '')
    {
        if ($level < $this->level) {
            return;
        }

        $requestId = $requestId? "|$requestId|" : '';
        echo trim(str_replace(
            ['%TIMESTAMP%', '%TYPE%', '%MESSAGE%', '%REQUEST_ID%'],
            [$this->getTimestamp(), self::LOG_LEVELS[$level], $message, $requestId],
            self::FORMAT
        )), "\n";
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function debug($message, $requestId = '')
    {
        $this->log(self::LOG_DEBUG, $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function info($message, $requestId = '')
    {
        $this->log(self::LOG_INFO, $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function warning($message, $requestId = '')
    {
        $this->log(self::LOG_WARNING, $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function error($message, $requestId = '')
    {
        $this->log(self::LOG_ERROR, $message, $requestId);
    }
}

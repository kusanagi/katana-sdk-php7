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
abstract class KatanaLogger
{
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
    public function __construct(int $level = null)
    {
        $this->level = $level ?? $this->level;
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
    protected function getTimestamp()
    {
        list($usec, $sec) = explode(" ", microtime());
        return sprintf(
            "%s.%dZ",
            (new \DateTime("@$sec"))->format('Y-m-d\TH:i:s'),
            round($usec * 1000)
        );
    }

    /**
     * @param int $level
     * @param string $message
     * @return string
     */
    abstract protected function formatMessage(int $level, string $message): string;

    /**
     * @param int $level
     * @param string $message
     */
    protected function log(int $level, string $message)
    {
        if ($level < $this->level) {
            return;
        }

        echo $this->formatMessage($level, $message), "\n";
    }

    /**
     * @param string $message
     */
    public function debug(string $message)
    {
        $this->log(self::LOG_DEBUG, $message);
    }

    /**
     * @param string $message
     */
    public function info(string $message)
    {
        $this->log(self::LOG_INFO, $message);
    }

    /**
     * @param string $message
     */
    public function warning(string $message)
    {
        $this->log(self::LOG_WARNING, $message);
    }

    /**
     * @param string $message
     */
    public function error(string $message)
    {
        $this->log(self::LOG_ERROR, $message);
    }
}

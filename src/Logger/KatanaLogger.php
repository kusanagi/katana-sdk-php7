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

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
abstract class KatanaLogger extends AbstractLogger implements LoggerInterface
{
    const LOG_EMERGENCY = 0;
    const LOG_ALERT = 1;
    const LOG_CRITICAL = 2;
    const LOG_ERROR = 3;
    const LOG_WARNING = 4;
    const LOG_NOTICE = 5;
    const LOG_INFO = 6;
    const LOG_DEBUG = 7;

    const LOG_LEVELS = [
        self::LOG_EMERGENCY => LogLevel::EMERGENCY,
        self::LOG_ALERT     => LogLevel::ALERT,
        self::LOG_CRITICAL  => LogLevel::CRITICAL,
        self::LOG_ERROR     => LogLevel::ERROR,
        self::LOG_WARNING   => LogLevel::WARNING,
        self::LOG_NOTICE    => LogLevel::NOTICE,
        self::LOG_INFO      => LogLevel::INFO,
        self::LOG_DEBUG     => LogLevel::DEBUG,
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
     * @return string
     */
    protected function getTimestamp(): string
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
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        if (!is_int($level)) {
            $level = array_search($level, self::LOG_LEVELS) ?? 6;
        }

        $level = max(0, $level);
        $level = min(7, $level);

        if ($level > $this->level) {
            return;
        }

        echo $this->formatMessage($level, $message), "\n";
    }
}

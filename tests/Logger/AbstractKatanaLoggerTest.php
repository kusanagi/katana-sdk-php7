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

namespace Katana\Sdk\Tests\Logger;

use Katana\Sdk\Logger\GlobalKatanaLogger;
use Katana\Sdk\Logger\KatanaLogger;
use PHPUnit\Framework\TestCase;

abstract class AbstractKatanaLoggerTest extends TestCase
{
    abstract protected function buildLogger(int $level): KatanaLogger;

    /**
     * Get the regexp that matches a log line
     *
     * @return string
     */
    abstract protected function getRegexpLine(): string;

    /**
     * Gets a log regexp for a given set of log levels and message
     *
     * @param array $logLevels
     * @param string $message
     * @return string
     */
    final protected function getRegExp(array $logLevels, string $message): string
    {
        $lines = array_map(function (string $logLevel) use ($message) {
            return sprintf($this->getRegexpLine(), $logLevel, $message);
        }, $logLevels);

        return '/' . implode("\n", $lines) . '/m';
    }

    /**
     * Test a GlobalKatanaLogger created on debug level
     */
    public function testDebugLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_DEBUG);

        $this->expectOutputRegex($this->getRegExp([
            'DEBUG',
            'INFO',
            'NOTICE',
            'WARNING',
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on info level
     */
    public function testInfoLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_INFO);

        $this->expectOutputRegex($this->getRegExp([
            'INFO',
            'NOTICE',
            'WARNING',
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on notice level
     */
    public function testNoticeLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_NOTICE);

        $this->expectOutputRegex($this->getRegExp([
            'NOTICE',
            'WARNING',
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on warning level
     */
    public function testWarningLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_WARNING);

        $this->expectOutputRegex($this->getRegExp([
            'WARNING',
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on error level
     */
    public function testErrorLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_ERROR);

        $this->expectOutputRegex($this->getRegExp([
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on critical level
     */
    public function testCriticalLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_CRITICAL);

        $this->expectOutputRegex($this->getRegExp([
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on alert level
     */
    public function testAlertLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_ALERT);

        $this->expectOutputRegex($this->getRegExp([
            'ALERT',
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }

    /**
     * Test a GlobalKatanaLogger created on emergency level
     */
    public function testEmergencyLogger()
    {
        $logger = $this->buildLogger(KatanaLogger::LOG_EMERGENCY);

        $this->expectOutputRegex($this->getRegExp([
            'EMERGENCY',
        ], 'Test log'));

        $logger->debug('Test log');
        $logger->info('Test log');
        $logger->notice('Test log');
        $logger->warning('Test log');
        $logger->error('Test log');
        $logger->critical('Test log');
        $logger->alert('Test log');
        $logger->emergency('Test log');
    }
}

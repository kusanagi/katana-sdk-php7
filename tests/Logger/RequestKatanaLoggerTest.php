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

namespace Katana\Sdk\Tests\Logger;

use Katana\Sdk\Logger\RequestKatanaLogger;
use Katana\Sdk\Logger\KatanaLogger;
use PHPUnit\Framework\TestCase;

class RequestKatanaLoggerTest extends TestCase
{
    public function testSkipDebugLog()
    {
        $logger = new RequestKatanaLogger('request_id');

        $this->expectOutputString('');
        $logger->debug('Test log');
    }

    public function testDebugLog()
    {
        $logger = new RequestKatanaLogger('request_id', KatanaLogger::LOG_DEBUG);

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z \[DEBUG\] \[SDK\] Test log |request_id|$/');
        $logger->debug('Test log');
    }

    public function testSkipInfoLog()
    {
        $logger = new RequestKatanaLogger('request_id', KatanaLogger::LOG_WARNING);

        $this->expectOutputString('');
        $logger->info('Test log');
    }

    public function testInfoLog()
    {
        $logger = new RequestKatanaLogger('request_id');

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z \[INFO\] \[SDK\] Test log |request_id|$/');
        $logger->info('Test log');
    }

    public function testSkipWarningLog()
    {
        $logger = new RequestKatanaLogger('request_id', KatanaLogger::LOG_ERROR);

        $this->expectOutputString('');
        $logger->warning('Test log');
    }

    public function testWarningLog()
    {
        $logger = new RequestKatanaLogger('request_id');

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z \[WARNING\] \[SDK\] Test log |request_id|$/');
        $logger->warning('Test log');
    }

    public function testErrorLog()
    {
        $logger = new RequestKatanaLogger('request_id', KatanaLogger::LOG_ERROR);

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z \[ERROR\] \[SDK\] Test log |request_id|$/');
        $logger->error('Test log');
    }
}

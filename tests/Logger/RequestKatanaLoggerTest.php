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

use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\RequestKatanaLogger;
use Katana\Sdk\Logger\KatanaLogger;
use PHPUnit\Framework\TestCase;

class RequestKatanaLoggerTest extends TestCase
{
    /**
     * @var CliInput
     */
    private $cliInput;

    public function setUp()
    {
        $cliInputProphecy = $this->prophesize(CliInput::class);
        $cliInputProphecy->getComponent()->willReturn('service');
        $cliInputProphecy->getName()->willReturn('test');
        $cliInputProphecy->getVersion()->willReturn('1.0.0');
        $cliInputProphecy->getFrameworkVersion()->willReturn('1.2.3');
        $this->cliInput = $cliInputProphecy->reveal();
    }

    public function testSkipDebugLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id');

        $this->expectOutputString('');
        $logger->debug('Test log');
    }

    public function testDebugLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id', KatanaLogger::LOG_DEBUG);

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z service test\/1.0.0 \(1.2.3\) \[DEBUG\] \[SDK\] Test log |request_id|$/');
        $logger->debug('Test log');
    }

    public function testSkipInfoLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id', KatanaLogger::LOG_WARNING);

        $this->expectOutputString('');
        $logger->info('Test log');
    }

    public function testInfoLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id');

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z service test\/1.0.0 \(1.2.3\)  \[INFO\] \[SDK\] Test log |request_id|$/');
        $logger->info('Test log');
    }

    public function testSkipWarningLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id', KatanaLogger::LOG_ERROR);

        $this->expectOutputString('');
        $logger->warning('Test log');
    }

    public function testWarningLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id');

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z service test\/1.0.0 \(1.2.3\)  \[WARNING\] \[SDK\] Test log |request_id|$/');
        $logger->warning('Test log');
    }

    public function testErrorLog()
    {
        $logger = new RequestKatanaLogger($this->cliInput, 'request_id', KatanaLogger::LOG_ERROR);

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z service test\/1.0.0 \(1.2.3\)  \[ERROR\] \[SDK\] Test log |request_id|$/');
        $logger->error('Test log');
    }
}

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
use Katana\Sdk\Logger\GlobalKatanaLogger;
use Katana\Sdk\Logger\KatanaLogger;

class GlobalKatanaLoggerTest extends AbstractKatanaLoggerTest
{
    protected function buildLogger(int $level): KatanaLogger
    {
        $cliProphecy = $this->prophesize(CliInput::class);
        $cliProphecy->getLogLevel()->willReturn($level);
        $cliProphecy->getComponent()->willReturn('service');
        $cliProphecy->getName()->willReturn('test');
        $cliProphecy->getVersion()->willReturn('1.0.0');
        $cliProphecy->getFrameworkVersion()->willReturn('1.2.3');

        return new GlobalKatanaLogger($cliProphecy->reveal());
    }


    protected function getRegexpLine(): string
    {
        return '^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z service test\/1\.0\.0 \(1\.2\.3\) \[%s\] \[SDK\] %s$';
    }
}

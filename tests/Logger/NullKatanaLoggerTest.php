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
use Katana\Sdk\Logger\NullKatanaLogger;
use PHPUnit\Framework\TestCase;

class NullKatanaLoggerTest extends TestCase
{
    /**
     * Get a NullKatanaLogger and test that it logs null for every level.
     */
    public function testSkipAllLogs()
    {
        $logger = new NullKatanaLogger();

        $this->expectOutputString('');
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

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

namespace Katana\Sdk\Api\Tests\Transport;

use Katana\Sdk\Api\Transport\ActionData;
use Katana\Sdk\Api\Transport\ServiceData;
use PHPUnit\Framework\TestCase;

class ServiceDataTest extends TestCase
{
    public function testEmptyServiceData()
    {
        $serviceData = new ServiceData('address', 'name', 'version', []);

        $this->assertEquals('address', $serviceData->getAddress());
        $this->assertEquals('name', $serviceData->getName());
        $this->assertEquals('version', $serviceData->getVersion());
        $this->assertEquals([], $serviceData->getActions());
    }

    public function testServiceDataWithActions()
    {
        $serviceData = new ServiceData('address', 'name', 'version', [
            $this->prophesize(ActionData::class)->reveal(),
            $this->prophesize(ActionData::class)->reveal(),
        ]);

        $this->assertEquals('address', $serviceData->getAddress());
        $this->assertEquals('name', $serviceData->getName());
        $this->assertEquals('version', $serviceData->getVersion());
        $this->assertCount(2, $serviceData->getActions());
        $this->assertContainsOnlyInstancesOf(ActionData::class, $serviceData->getActions());
    }
}

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

namespace Katana\Sdk\Tests\Api\Transport;

use Katana\Sdk\Api\Transport\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testLink()
    {
        $link = new Link('address', 'name', 'link', 'uri');

        $this->assertEquals($link->getAddress(), 'address');
        $this->assertEquals($link->getName(), 'name');
        $this->assertEquals($link->getLink(), 'link');
        $this->assertEquals($link->getUri(), 'uri');
    }
}

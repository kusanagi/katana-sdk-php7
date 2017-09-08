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

namespace Katana\Sdk\Tests\Mapper;

use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\File;
use Katana\Sdk\Mapper\CompactRuntimeCallMapper;
use Katana\Sdk\Mapper\RuntimeCallWriterInterface;
use Katana\Sdk\Mapper\TransportWriterInterface;
use Katana\Sdk\Param;
use Katana\Sdk\Api\Transport;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

abstract class AbstractRuntimeCallMapperTest extends TestCase
{
    abstract function getSystemUnderTest(TransportWriterInterface $transpotMapper): RuntimeCallWriterInterface;

    abstract protected function getExpectedOutput(): array;

    public function testMapping()
    {
        $transportMapper = $this->prophesize(TransportWriterInterface::class);
        $transportMapper->writeTransport(Argument::any())->willReturn([]);
        $transport = $this->prophesize(Transport::class);

        $target = new ActionTarget(
            'target_service',
            new VersionString('1.0.1'),
            'target_action'
        );

        $param = $this->prophesize(Param::class);
        $param->getName()->willReturn('param');
        $param->getValue()->willReturn('value');
        $param->getType()->willReturn('string');

        $file = $this->prophesize(File::class);
        $file->getName()->willReturn('file_name');
        $file->getPath()->willReturn('http://12.34.56.78:1234/files/ac3bd4b8-7da3-4c40-8661-746adfa55e0d');
        $file->getToken()->willReturn('fb9an6c46be74s425010896fcbd99e2a');
        $file->getFilename()->willReturn('smiley.jpg');
        $file->getSize()->willReturn(1234567890);
        $file->getMime()->willReturn('image/jpeg');

        $mapper = $this->getSystemUnderTest($transportMapper->reveal());

        $output = $mapper->writeRuntimeCall(
            'action',
            $transport->reveal(),
            $target,
            [$param->reveal()],
            [$file->reveal()]);

        $this->assertEquals($this->getExpectedOutput(), $output);
    }
}

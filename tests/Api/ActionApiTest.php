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

namespace Katana\Sdk\Tests\Api;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\AbstractCall;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TypeCatalog;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\RuntimeCaller\ZeroMQRuntimeCaller;
use Katana\Sdk\Schema\ActionSchema;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ActionApiTest extends TestCase
{
    /**
     * @var ActionApi
     */
    private $action;

    /**
     * @var KatanaLogger
     */
    private $logger;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var ServiceSchema
     */
    private $serviceSchema;

    public function setUp()
    {
        $this->logger = $this->prophesize(KatanaLogger::class);
        $this->logger->getLevel()->willReturn(KatanaLogger::LOG_DEBUG);

        $this->service = $this->prophesize(ServiceSchema::class);

        $mapping = $this->prophesize(Mapping::class);
        $mapping->find(
            Argument::any(), Argument::any()
        )->willReturn($this->service->reveal());

        $meta = $this->prophesize(TransportMeta::class);
        $meta->getGateway()->willReturn('127.0.0.1:80');

        $this->transport = $this->prophesize(Transport::class);
        $this->transport->getMeta()->willReturn($meta);

        $caller = $this->prophesize(ZeroMQRuntimeCaller::class);

        $this->action = new ActionApi(
            $this->logger->reveal(),
            $this->prophesize(Component::class)->reveal(),
            $mapping->reveal(),
            '/',
            'test',
            '1.0',
            '1.0.0',
            [],
            true,
            'action',
            $caller->reveal(),
            $this->transport->reveal(),
            new TypeCatalog()
        );
    }

    public function testNewFile()
    {
        $file = $this->action->newFile('file', __DIR__ . '/file.txt');
        $this->assertInstanceOf(File::class, $file);
    }

    public function testSetDownload()
    {
        $this->service->hasFileServer()->willReturn(true);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(false);
        $file = $file->reveal();

        $this->transport->setBody($file)->shouldBeCalled();
        $this->action->setDownload($file);
    }

    public function testSetLocalFileWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('File server not configured: "test" (1.0)');
        $this->action->setDownload($file->reveal());
    }

    public function testSetRemoteFileWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(false);
        $file = $file->reveal();

        $this->transport->setBody($file)->shouldBeCalled();
        $this->action->setDownload($file);
    }

    public function testOverrideDownloadFile()
    {
        $this->service->hasFileServer()->willReturn(true);

        /** @var File $file1 */
        $file1 = $this->prophesize(File::class);
        $file1->isLocal()->willReturn(false);
        $file1 = $file1->reveal();
        /** @var File $file2 */
        $file2 = $this->prophesize(File::class);
        $file2->isLocal()->willReturn(false);
        $file2 = $file2->reveal();

        $this->transport->setBody($file1)->shouldBeCalled();
        $this->action->setDownload($file1);
        $this->transport->setBody($file2)->shouldBeCalled();
        $this->action->setDownload($file2);
    }

    public function testCallWithLocalFilesWithoutServer()
    {
        $action = $this->prophesize(ActionSchema::class);
        $action->hasDeferCall(Argument::any(), Argument::any(), Argument::any())
            ->willReturn(true);

        $this->service->hasFileServer()->willReturn(false);
        $this->service->getAddress()->willReturn('1.1.1.1:11');
        $this->service->getActionSchema(Argument::any())->willReturn($action->reveal());

        $this->transport->addCall(Argument::type(AbstractCall::class))->shouldBeCalled();

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('File server not configured: "test" (1.0)');
        $this->action->deferCall('service', '1.0.0', 'action', [], [$file->reveal()]);
    }

    public function testCallWithLocalFilesWithServer()
    {
        $action = $this->prophesize(ActionSchema::class);
        $action->hasDeferCall(Argument::any(), Argument::any(), Argument::any())
            ->willReturn(true);

        $this->service->hasFileServer()->willReturn(true);
        $this->service->getAddress()->willReturn('1.1.1.1:11');
        $this->service->getActionSchema(Argument::any())->willReturn($action->reveal());

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);
        $file = $file->reveal();

        $this->transport->addCall(Argument::any())->willReturn(true);
        $this->transport->addFile(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            $file
        )->shouldBeCalled();

        $this->action->deferCall('service', '1.0.0', 'action', [], [$file]);
    }

    public function testCallWithRemoteFilesWithoutServer()
    {
        $action = $this->prophesize(ActionSchema::class);
        $action->hasDeferCall(Argument::any(), Argument::any(), Argument::any())
            ->willReturn(true);

        $this->service->hasFileServer()->willReturn(false);
        $this->service->getAddress()->willReturn('1.1.1.1:11');
        $this->service->getActionSchema(Argument::any())->willReturn($action->reveal());

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(false);
        $file = $file->reveal();

        $this->transport->addCall(Argument::any())->willReturn(true);
        $this->transport->addFile(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            $file
        )->shouldBeCalled();

        $this->action->deferCall('service', '1.0.0', 'action', [], [$file]);
    }
}

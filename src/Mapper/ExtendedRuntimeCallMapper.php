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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\File;
use Katana\Sdk\Param;

class ExtendedRuntimeCallMapper implements RuntimeCallWriterInterface
{
    /**
     * @var TransportWriterInterface
     */
    private $transportMapper;

    /**
     * @param TransportWriterInterface $transportMapper
     */
    public function __construct(TransportWriterInterface $transportMapper)
    {
        $this->transportMapper = $transportMapper;
    }

    /**
     * @param Param $param
     * @return array
     */
    private function writeParam(Param $param)
    {
        return [
            'name' => $param->getName(),
            'value' => $param->getValue(),
            'type' => $param->getType(),
        ];
    }

    /**
     * @param File $file
     * @return array
     */
    private function writeFile(File $file)
    {
        return [
            'name' => $file->getName(),
            'path' => $file->getPath(),
            'token' => $file->getToken(),
            'filename' => $file->getFilename(),
            'size' => $file->getSize(),
            'mime' => $file->getMime()
        ];
    }

    /**
     * @param string $action
     * @param Transport $transport
     * @param ActionTarget $target
     * @param Param[] $params
     * @param File[] $files
     * @return array
     */
    public function writeRuntimeCall(
        string $action,
        Transport $transport,
        ActionTarget $target,
        array $params = [],
        array $files = []
    ): array {
        return [
            'command' => [
                'name' => 'runtime-call',
                'arguments' => [
                    'action' => $action,
                    'callee' => [
                        $target->getService(),
                        $target->getVersion()->getVersion(),
                        $target->getAction()
                    ],
                    'transport' => $this->transportMapper->writeTransport($transport),
                    'params' => array_map([$this, 'writeParam'], $params),
                    'files' => array_map([$this, 'writeFile'], $files),
                ],
            ],
            'meta' => [
                'scope' => 'service',
            ],
        ];
    }
}

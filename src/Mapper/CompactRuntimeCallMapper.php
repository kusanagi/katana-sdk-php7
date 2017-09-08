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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\File;
use Katana\Sdk\Param;

class CompactRuntimeCallMapper implements RuntimeCallWriterInterface
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
            'n' => $param->getName(),
            'v' => $param->getValue(),
            't' => $param->getType(),
        ];
    }

    /**
     * @param File $file
     * @return array
     */
    private function writeFile(File $file)
    {
        return [
            'n' => $file->getName(),
            'p' => $file->getPath(),
            't' => $file->getToken(),
            'f' => $file->getFilename(),
            's' => $file->getSize(),
            'm' => $file->getMime()
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
            'c' => [
                'n' => 'runtime-call',
                'a' => [
                    'a' => $action,
                    'c' => [
                        $target->getService(),
                        $target->getVersion()->getVersion(),
                        $target->getAction()
                    ],
                    'T' => $this->transportMapper->writeTransport($transport),
                    'p' => array_map([$this, 'writeParam'], $params),
                    'f' => array_map([$this, 'writeFile'], $files),
                ],
            ],
            'm' => [
                's' => 'service',
            ],
        ];
    }
}

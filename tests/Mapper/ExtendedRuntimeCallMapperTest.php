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

use Katana\Sdk\Mapper\ExtendedRuntimeCallMapper;
use Katana\Sdk\Mapper\RuntimeCallWriterInterface;
use Katana\Sdk\Mapper\TransportWriterInterface;

class ExtendedRuntimeCallMapperTest extends AbstractRuntimeCallMapperTest
{
    function getSystemUnderTest(TransportWriterInterface $transportMapper): RuntimeCallWriterInterface
    {
        return new ExtendedRuntimeCallMapper($transportMapper);
    }

    /**
     * @return array
     */
    protected function getExpectedOutput(): array
    {
        return [
            'command' => [
                'name' => 'runtime-call',
                'arguments' => [
                    'action' => 'action',
                    'callee' => [
                        'target_service',
                        '1.0.1',
                        'target_action'
                    ],
                    'transport' => [],
                    'params' => [
                        [
                            'name' => 'param',
                            'value' => 'value',
                            'type' => 'string',
                        ]
                    ],
                    'files' => [
                        [
                            'name' => 'file_name',
                            'path' => 'http://12.34.56.78:1234/files/ac3bd4b8-7da3-4c40-8661-746adfa55e0d',
                            'token' => 'fb9an6c46be74s425010896fcbd99e2a',
                            'filename' => 'smiley.jpg',
                            'size' => 1234567890,
                            'mime' => 'image/jpeg',
                        ]
                    ],
                ],
            ],
            'meta' => [
                'scope' => 'service',
            ],
        ];
    }
}

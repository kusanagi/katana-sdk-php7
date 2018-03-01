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

namespace Katana\Sdk\Tests\Mapper;

use Katana\Sdk\Mapper\CompactRuntimeCallMapper;
use Katana\Sdk\Mapper\RuntimeCallWriterInterface;
use Katana\Sdk\Mapper\TransportWriterInterface;

class CompactRuntimeCallMapperTest extends AbstractRuntimeCallMapperTest
{
    function getSystemUnderTest(TransportWriterInterface $transportMapper): RuntimeCallWriterInterface
    {
        return new CompactRuntimeCallMapper($transportMapper);
    }

    /**
     * @return array
     */
    protected function getExpectedOutput(): array
    {
        return [
            'c' => [
                'n' => 'runtime-call',
                'a' => [
                    'a' => 'action',
                    'c' => [
                        'target_service',
                        '1.0.1',
                        'target_action'
                    ],
                    'T' => [],
                    'p' => [
                        [
                            'n' => 'param',
                            'v' => 'value',
                            't' => 'string',
                        ]
                    ],
                    'f' => [
                        [
                            'n' => 'file_name',
                            'p' => 'http://12.34.56.78:1234/files/ac3bd4b8-7da3-4c40-8661-746adfa55e0d',
                            't' => 'fb9an6c46be74s425010896fcbd99e2a',
                            'f' => 'smiley.jpg',
                            's' => 1234567890,
                            'm' => 'image/jpeg',
                        ]
                    ],
                ],
            ],
            'm' => [
                's' => 'service',
            ],
        ];
    }
}

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

use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Value\ActionTarget;
use Katana\Sdk\File;
use Katana\Sdk\Param;

interface RuntimeCallWriterInterface
{
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
    ): array;
}
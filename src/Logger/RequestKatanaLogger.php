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

namespace Katana\Sdk\Logger;

use Katana\Sdk\Console\CliInput;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
class RequestKatanaLogger extends GlobalKatanaLogger
{
    /**
     * @param CliInput $input
     * @param string $requestId
     */
    public function __construct(CliInput $input, string $requestId)
    {
        $this->requestId = $requestId;
        parent::__construct($input);
    }

    /**
     * @var string
     */
    private $requestId = '';

    /**
     * @param int $level
     * @param string $message
     * @return string
     * @throws \Exception
     */
    protected function formatMessage(int $level, string $message): string
    {
        return parent::formatMessage($level, $message) . " |{$this->requestId}|";
    }
}

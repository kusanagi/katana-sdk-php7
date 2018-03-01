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

use Exception;
use Katana\Sdk\Console\CliInput;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
class GlobalKatanaLogger extends KatanaLogger
{
    /**
     * @var CliInput
     */
    private $input;

    /**
     * @param CliInput $input
     */
    public function __construct(CliInput $input)
    {
        $this->input = $input;
        parent::__construct($input->getLogLevel());
    }


    /**
     * @param int $level
     * @param string $message
     * @return string
     * @throws Exception
     */
    protected function formatMessage(int $level, string $message): string
    {
        return trim(str_replace(
            ['%TIMESTAMP%', '%COMPONENT%', '%FRAMEWORK_VERSION%', '%TYPE%', '%MESSAGE%'],
            [
                $this->getTimestamp(),
                "{$this->input->getComponent()} {$this->input->getName()}/{$this->input->getVersion()}",
                $this->input->getFrameworkVersion(),
                strtoupper(self::LOG_LEVELS[$level]),
                $message
            ],
            '%TIMESTAMP% %COMPONENT% (%FRAMEWORK_VERSION%) [%TYPE%] [SDK] %MESSAGE%'
        ));
    }

    /**
     * @param string $requestId
     * @return RequestKatanaLogger
     */
    public function getRequestLogger(string $requestId): RequestKatanaLogger
    {
        return new RequestKatanaLogger($this->input, $requestId, $this->getLevel());
    }
}

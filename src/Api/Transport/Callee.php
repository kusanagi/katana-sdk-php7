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

namespace Katana\Sdk\Api\Transport;

use Katana\Sdk\Param;

class Callee
{
    /**
     * @var int
     */
    private $timeout = 0;

    /**
     * @var int
     */
    private $duration = 0;

    /**
     * @var string
     */
    private $address = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var Param[]
     */
    private $params = [];

    /**
     * @param int $timeout
     * @param int $duration
     * @param string $address
     * @param string $name
     * @param string $version
     * @param string $action
     * @param Param[] $params
     */
    public function __construct(
        int $timeout,
        int $duration,
        string $address,
        string $name,
        string $version,
        string $action,
        array $params
    ) {
        $this->timeout = $timeout;
        $this->duration = $duration;
        $this->address = $address;
        $this->name = $name;
        $this->version = $version;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return bool
     */
    public function isRemote(): bool
    {
        return !empty($this->address);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return Param[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}

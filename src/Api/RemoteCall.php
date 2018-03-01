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

namespace Katana\Sdk\Api;

use Katana\Sdk\Api\Value\VersionString;

class RemoteCall extends AbstractCall
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @param ServiceOrigin $origin
     * @param string $caller
     * @param string $address
     * @param string $service
     * @param VersionString $version
     * @param string $action
     * @param int $duration
     * @param int $timeout
     * @param Param[] $params
     * @param File[] $files
     */
    public function __construct(
        ServiceOrigin $origin,
        $caller,
        $address,
        $service,
        VersionString $version,
        $action,
        int $duration,
        $timeout,
        $params = [],
        $files = []
    ) {
        parent::__construct($origin, $caller, $service, $version, $action, $duration, $params, $files);
        $this->address = $address;
        $this->timeout = $timeout;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}

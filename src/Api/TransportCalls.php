<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Api;

/**
 * Support Transport Api class that encapsulates a list of calls.
 *
 * @package Katana\Sdk\Api
 */
class TransportCalls
{
    /**
     * @var DeferCall[]
     */
    private $calls = [];

    /**
     * @param DeferCall[] $calls
     */
    public function __construct(array $calls = [])
    {
        $this->calls = $calls;
    }

    /**
     * @param DeferCall $call
     * @return bool
     */
    public function add(DeferCall $call)
    {
        $this->calls[] = $call;

        return true;
    }

    /**
     * @return bool
     */
    public function has()
    {
        return !empty($this->calls);
    }

    /**
     * @param string $service
     * @return DeferCall[]
     */
    public function get($service = '')
    {
        $calls = $this->calls;
        if ($service) {
            $calls = isset($calls[$service])? $calls[$service] : [];
        }

        return $calls;
    }

    /**
     * @param string $service
     * @return array
     */
    public function getArray($service = '')
    {
        $calls = [];
        foreach ($this->calls as $call) {
            $origin = $call->getOrigin();
            if ($service && $origin->getName() !== $service) {
                continue;
            }

            $callOutput = [
                'n' => $call->getService(),
                'v' => $call->getVersion(),
                'a' => $call->getAction(),
                'p' => array_map(function (Param $param) {
                    return [
                        'n' => $param->getName(),
                        'v' => $param->getValue(),
                        't' => $param->getType(),
                    ];
                }, $call->getParams()),
            ];

            if ($service) {
                $calls[$origin->getVersion()][] = $callOutput;
            } else {
                $calls[$origin->getName()][$origin->getVersion()][] = $callOutput;
            }
        }

        return $calls;
    }
}

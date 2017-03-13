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
 * Support Transport Api class that encapsulates a list of relations.
 * @package Katana\Sdk\Api
 */
class TransportRelations
{
    /**
     * @var array
     */
    private $relations = [];

    /**
     * @param array $relations
     */
    public function __construct(array $relations = [])
    {
        $this->relations = $relations;
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function get($address = '', $service = '')
    {
        $relations = $this->relations;
        if ($address) {
            $relations = isset($relations[$address])? $relations[$address] : [];

            if ($service) {
                $relations = isset($relations[$service])? $relations[$service] : [];
            }
        }

        return $relations;
    }

    /**
     * @param $addressFrom
     * @param string $serviceFrom
     * @param string $idFrom
     * @param $addressTo
     * @param string $serviceTo
     * @param string $idTo
     * @return bool
     * @internal param string $address
     */
    public function addSimple($addressFrom, $serviceFrom, $idFrom, $addressTo, $serviceTo, $idTo)
    {
        $this->relations[$addressFrom][$serviceFrom][$idFrom][$addressTo][$serviceTo] = $idTo;

        return true;
    }

    /**
     * @param $addressFrom
     * @param string $serviceFrom
     * @param string $idFrom
     * @param $addressTo
     * @param string $serviceTo
     * @param array $idsTo
     * @return bool
     * @internal param string $address
     */
    public function addMultipleRelation($addressFrom, $serviceFrom, $idFrom, $addressTo, $serviceTo, array $idsTo)
    {
        $this->relations[$addressFrom][$serviceFrom][$idFrom][$addressTo][$serviceTo] = $idsTo;

        return true;
    }
}

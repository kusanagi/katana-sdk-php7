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
 * Support Transport Api class that encapsulates a list of errors.
 * @package Katana\Sdk\Api
 */
class TransportErrors
{
    /**
     * @var Error[]
     */
    private $errors = [];

    /**
     * @param Error[] $errors
     */
    public function __construct($errors = [])
    {
        $this->errors = $errors;
    }

    /**
     * @param Error $error
     * @return bool
     */
    public function add(Error $error)
    {
        $this->errors[] = $error;

        return true;
    }

    /**
     * @param string $address
     * @param string $service
     * @return Error[]
     */
    public function get($address = '', $service = '')
    {
        $errors = $this->errors;
        if ($address) {
            $errors = isset($errors[$address])? $errors[$address] : [];

            if ($service) {
                $errors = isset($errors[$service])? $errors[$service] : [];
            }
        }

        return $errors;
    }

    /**
     * @param string $address
     * @param string $service
     * @return array
     */
    public function getArray($address = '', $service = '')
    {
        $errors = [];
        foreach ($this->errors as $error) {
            if ($address && $error->getAddress() !== $address) {
                echo "Skip address $address\n";
                continue;
            } elseif ($service && $error->getService() !== $service) {
                echo "Skip service $service\n";
                continue;
            }

            $errorOutput = [
                'm' => $error->getMessage(),
                'c' => $error->getCode(),
                's' => $error->getStatus(),
            ];

            if ($address) {
                $errors[$error->getService()][$error->getVersion()][] = $errorOutput;
            } elseif ($service) {
                $errors[$error->getService()][] = $errorOutput;
            } else {
                $errors[$error->getAddress()][$error->getService()][$error->getVersion()][] = $errorOutput;
            }
        }

        return $errors;
    }
}

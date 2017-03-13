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

namespace Katana\Sdk\Api\Value;

use Katana\Sdk\Exception\InvalidValueException;

class VersionString
{
    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     * @throws InvalidValueException
     */
    public function __construct($version)
    {
        if (preg_match('/[^a-zA-Z0-9*.,_-]/', $version)) {
            throw new InvalidValueException("Invalid version: $version");
        }

        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return int
     */
    public function match($version)
    {
        $regex = preg_replace('/\*+/', '[^*.]+', $this->version);

        return (bool) preg_match("/^$regex$/", $version);
    }

    /**
     * @param array $versions
     * @return string
     */
    public function resolve(array $versions)
    {
        $valid = array_filter($versions, [$this, 'match']);
        usort($valid, [$this, 'compare']);

        return end($valid) ?: '';
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    private function compareNull($a, $b)
    {
        if ($a === null && $b === null) {
            return 0;
        }

        if ($b === null) {
            return -1;
        }

        if ($a === null) {
            return 1;
        }
    }

    /**
     * @param string $a
     * @param string $b
     * @return int
     */
    private function compare($a, $b)
    {
        if ($a === $b) {
            return 0;
        }

        $a = explode('.', $a);
        $b = explode('.', $b);

        while(true) {
            $partA = array_shift($a);
            $partB = array_shift($b);

            if ($partA === null || $partB === null) {
                return $this->compareNull($partA, $partB);
            }

            $subA = explode('-', $partA);
            $subB = explode('-', $partB);

            while(true) {
                $currentA = array_shift($subA);
                $currentB = array_shift($subB);

                if ($currentA === null || $currentB === null) {
                    $compare = $this->compareNull($currentA, $currentB);
                    if ($compare === 0) {
                        break;
                    } else {
                        return $compare;
                    }
                }

                $compare = $this->compareParts($currentA, $currentB);

                if ($compare !== 0) {
                    return $compare;
                }
            }
        }

        return 0;
    }

    /**
     * @param string $a
     * @param string $b
     * @return int
     */
    private function compareParts($a, $b)
    {
        if ($a === $b) {
            return 0;
        }

        $isIntA = is_numeric($a);
        $isIntB = is_numeric($b);

        if ($isIntA !== $isIntB) {
            return $isIntA ? 1 : -1;
        }

        if ($isIntA && $isIntB) {
            return ((int) $a < (int) $b) ? -1 : 1;
        }

        return ($a > $b) ? -1 : 1;
    }
}

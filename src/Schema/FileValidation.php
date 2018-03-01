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

namespace Katana\Sdk\Schema;

class FileValidation
{
    /**
     * Defines the maximum size allowed for the given file.
     *
     * @var int
     */
    private $maximum = PHP_INT_MAX;

    /**
     * Defines that the file size MUST be less than maximum.
     *
     * @var bool
     */
    private $exclusiveMaximum = false;

    /**
     * Defines the minimum size allowed for the given file.
     *
     * @var int
     */
    private $minimum = 0;

    /**
     * Defines that the file size MUST be greater than minimum.
     *
     * @var bool
     */
    private $exclusiveMinimum = false;

    /**
     * @param int $maximum
     * @param bool $exclusiveMax
     * @param int $minimum
     * @param bool $exclusiveMinimum
     */
    public function __construct($maximum, $exclusiveMax, $minimum, $exclusiveMinimum)
    {
        $this->maximum = $maximum;
        $this->exclusiveMaximum = $exclusiveMax;
        $this->minimum = $minimum;
        $this->exclusiveMinimum = $exclusiveMinimum;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->maximum;
    }

    /**
     * @return boolean
     */
    public function isExclusiveMax()
    {
        return $this->exclusiveMaximum;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->minimum;
    }

    /**
     * @return boolean
     */
    public function isExclusiveMin()
    {
        return $this->exclusiveMinimum;
    }
}

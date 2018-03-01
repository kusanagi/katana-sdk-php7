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

class ParamValidation
{
    /**
     * Defines an ECMA 262 compliant regular expression to validate the value.
     *
     * @var string
     */
    private $pattern = '';

    /**
     * Defines the maximum value allowed for the given parameter.
     *
     * @var int
     */
    private $maximum = PHP_INT_MAX;

    /**
     * Defines that the value of the parameter MUST be less than maximum.
     *
     * @var bool
     */
    private $exclusiveMaximum = false;

    /**
     * Defines the minimum value allowed for the given parameter.
     *
     * @var int
     */
    private $minimum = ~PHP_INT_MAX;

    /**
     * Defines that the value of the parameter MUST be greater than minimum.
     *
     * @var bool
     */
    private $exclusiveMinimum = false;

    /**
     * Defines the maximum length of the parameter if type is set to string.
     *
     * @var int
     */
    private $maximumLength = -1;

    /**
     * Defines the minimum length of the parameter if type is set to string.
     *
     * @var int
     */
    private $minimumLength = -1;

    /**
     * Defines the maximum size of the parameter if type is set to array.
     *
     * @var int
     */
    private $maximumItems = -1;

    /**
     * Defines the minimum size of the parameter if type is set to array.
     *
     * @var int
     */
    private $minimumItems = -1;

    /**
     * Defines that a parameter MUST contain a set of unique elements.
     *
     * @var bool
     */
    private $uniqueItems = false;

    /**
     * Defines a list of unique values that the value of the parameter MUST equal.
     *
     * @var array
     */
    private $enum = [];

    /**
     * Defines that the division of the parameter by this value MUST be an integer.
     *
     * @var int
     */
    private $multipleOf = -1;

    /**
     * @param string $pattern
     * @param int $maximum
     * @param bool $exclusiveMaximum
     * @param int $minimum
     * @param bool $exclusiveMinimum
     * @param int $maximumLength
     * @param int $minimumLength
     * @param int $maximumItems
     * @param int $minimumItems
     * @param bool $uniqueItems
     * @param array $enum
     * @param int $multipleOf
     */
    public function __construct(
        $pattern,
        $maximum,
        $exclusiveMaximum,
        $minimum,
        $exclusiveMinimum,
        $maximumLength,
        $minimumLength,
        $maximumItems,
        $minimumItems,
        $uniqueItems,
        array $enum,
        $multipleOf
    ) {
        $this->pattern = $pattern;
        $this->maximum = $maximum;
        $this->exclusiveMaximum = $exclusiveMaximum;
        $this->minimum = $minimum;
        $this->exclusiveMinimum = $exclusiveMinimum;
        $this->maximumLength = $maximumLength;
        $this->minimumLength = $minimumLength;
        $this->maximumItems = $maximumItems;
        $this->minimumItems = $minimumItems;
        $this->uniqueItems = $uniqueItems;
        $this->enum = $enum;
        $this->multipleOf = $multipleOf;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
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

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->maximumLength;
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->minimumLength;
    }

    /**
     * @return int
     */
    public function getMaxItems()
    {
        return $this->maximumItems;
    }

    /**
     * @return int
     */
    public function getMinItems()
    {
        return $this->minimumItems;
    }

    /**
     * @return boolean
     */
    public function hasUniqueItems()
    {
        return $this->uniqueItems;
    }

    /**
     * @return array
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @return int
     */
    public function getMultipleOf()
    {
        return $this->multipleOf;
    }

}

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

use Katana\Sdk\Schema\Protocol\HttpParamSchema;

class ParamSchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var HttpParamSchema
     */
    private $http;

    /**
     * @var ParamType
     */
    private $type;

    /**
     * @var ParamExpectation
     */
    private $expectation;

    /**
     * @var ParamValidation
     */
    private $validation;

    /**
     * @param string $name
     * @param HttpParamSchema $http
     * @param ParamType $type
     * @param ParamExpectation $expectation
     * @param ParamValidation $validation
     */
    public function __construct(
        $name,
        HttpParamSchema $http,
        ParamType $type,
        ParamExpectation $expectation,
        ParamValidation $validation
    ) {
        $this->name = $name;
        $this->http = $http;
        $this->type = $type;
        $this->expectation = $expectation;
        $this->validation = $validation;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type->getType();
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->type->getFormat();
    }

    /**
     * @return string
     */
    public function getArrayFormat()
    {
        return $this->type->getArrayFormat();
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->validation->getPattern();
    }

    /**
     * @return bool
     */
    public function allowEmpty()
    {
        return $this->expectation->allowEmpty();
    }

    /**
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->expectation->hasDefault();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->expectation->getDefault();
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->expectation->isRequired();
    }

    /**
     * @return string
     */
    public function getItems()
    {
        return $this->type->getItems();
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->validation->getMax();
    }

    /**
     * @return bool
     */
    public function isExclusiveMax()
    {
        return $this->validation->isExclusiveMax();
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->validation->getMin();
    }

    /**
     * @return bool
     */
    public function isExclusiveMin()
    {
        return $this->validation->isExclusiveMin();
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->validation->getMaxLength();
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->validation->getMinLength();
    }

    /**
     * @return int
     */
    public function getMaxItems()
    {
        return $this->validation->getMaxItems();
    }

    /**
     * @return int
     */
    public function getMinItems()
    {
        return $this->validation->getMinItems();
    }

    /**
     * @return bool
     */
    public function hasUniqueItems()
    {
        return $this->validation->hasUniqueItems();
    }

    /**
     * @return array
     */
    public function getEnum()
    {
        return $this->validation->getEnum();
    }

    /**
     * @return int
     */
    public function getMultipleOf()
    {
        return $this->validation->getMultipleOf();
    }

    /**
     * @return HttpParamSchema
     */
    public function getHttpSchema()
    {
        return $this->http;
    }
}

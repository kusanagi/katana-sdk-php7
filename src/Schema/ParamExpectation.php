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

class ParamExpectation
{
    /**
     * Defines a default value if none is provided.
     *
     * MUST conform with the value defined by type
     *
     * @var mixed
     */
    private $default;

    /**
     * Defines whether the parameter is required or not.
     *
     * @var bool
     */
    private $required = false;

    /**
     * Determines if an empty value MAY be allowed for the parameter.
     *
     * @var bool
     */
    private $allowEmpty = false;

    /**
     * @param mixed $default
     * @param bool $required
     * @param bool $allowEmpty
     */
    public function __construct($default, $required, $allowEmpty)
    {
        $this->default = $default;
        $this->required = $required;
        $this->allowEmpty = $allowEmpty;
    }

    /**
     * @return bool
     */
    public function hasDefault()
    {
        return $this->default !== null;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return boolean
     */
    public function allowEmpty()
    {
        return $this->allowEmpty;
    }
}

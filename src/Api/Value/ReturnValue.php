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

namespace Katana\Sdk\Api\Value;

use Katana\Sdk\Exception\InvalidValueException;

class ReturnValue
{
    /**
     * @var bool
     */
    private $exists = false;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param null $value
     * @param bool $nullValue Pass true if the value is null, ignored otherwise.
     */
    public function __construct($value = null, $nullValue = false)
    {
        $this->exists = !is_null($value) || $nullValue;
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * @return mixed
     * @throws InvalidValueException
     */
    public function getValue()
    {
        if (!$this->exists) {
            throw new InvalidValueException('Invalid return');
        }
        return $this->value;
    }
}

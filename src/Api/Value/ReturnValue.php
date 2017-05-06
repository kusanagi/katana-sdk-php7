<?php

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

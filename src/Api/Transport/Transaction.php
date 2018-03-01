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

namespace Katana\Sdk\Api\Transport;

use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Param;

class Transaction
{
    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $callerAction = '';

    /**
     * @var string
     */
    private $calleeAction = '';

    /**
     * @var Param[]
     */
    private $params = [];

    /**
     * @param string $type
     * @param string $name
     * @param string $version
     * @param string $callerAction
     * @param string $calleeAction
     * @param Param[] $params
     * @throws InvalidValueException
     */
    public function __construct(
        string $type,
        string $name,
        string $version,
        string $callerAction,
        string $calleeAction,
        array $params
    ) {
        if (!in_array($type, ['commit', 'rollback', 'complete'])) {
            throw new InvalidValueException("Invalid type of transaction: $type");
        }

        $this->type = $type;
        $this->name = $name;
        $this->version = $version;
        $this->callerAction = $callerAction;
        $this->calleeAction = $calleeAction;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getCallerAction(): string
    {
        return $this->callerAction;
    }

    /**
     * @return string
     */
    public function getCalleeAction(): string
    {
        return $this->calleeAction;
    }

    /**
     * @return Param[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}

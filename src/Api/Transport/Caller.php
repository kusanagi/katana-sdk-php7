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

class Caller
{
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
    private $action = '';

    /**
     * @var Callee
     */
    private $callee;

    /**
     * @param string $name
     * @param string $version
     * @param string $action
     * @param Callee $callee
     */
    public function __construct(string $name, string $version, string $action, Callee $callee)
    {
        $this->name = $name;
        $this->version = $version;
        $this->action = $action;
        $this->callee = $callee;
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
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return Callee
     */
    public function getCallee(): Callee
    {
        return $this->callee;
    }
}

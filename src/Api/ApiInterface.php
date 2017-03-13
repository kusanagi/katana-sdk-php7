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

use Katana\Sdk\Schema\ServiceSchema;

interface ApiInterface
{
    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return string
     */
    public function getFrameworkVersion(): string;

    /**
     * @return array
     */
    public function getVariables(): array;

    /**
     * @param $name
     * @return string
     */
    public function getVariable(string $name): string;

    /**
     * @return boolean
     */
    public function isDebug(): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function hasResource(string $name): bool;

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource(string $name);

    /**
     * @param mixed $value
     * @return bool
     */
    public function log($value): bool;

    /**
     * @return array
     */
    public function getServices(): array;

    /**
     * @param string $name
     * @param string $version
     * @return ServiceSchema
     */
    public function getServiceSchema(
        string $name,
        string $version
    ): ServiceSchema;
}

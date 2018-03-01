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

namespace Katana\Sdk;

use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadMapperInterface;
use Katana\Sdk\Component\Component;

/**
 * Service class that can run actions
 *
 * @package Katana\Sdk
 */
class Service extends Component
{
    /**
     * @param string $name
     * @param callable $callback
     * @return Service
     */
    public function action(string $name, callable $callback): Service
    {
        $this->setCallback($name, $callback);

        return $this;
    }

    /**
     * @param PayloadMapperInterface $mapper
     * @return ApiFactory
     */
    protected function getApiFactory(PayloadMapperInterface $mapper): ApiFactory
    {
        return ApiFactory::getServiceFactory($this, $mapper, $this->logger);
    }
}

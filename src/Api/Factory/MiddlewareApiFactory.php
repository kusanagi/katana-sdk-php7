<?php
/**
 * PHP 7 SDK for the KATANA(tm) Framework (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php7
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Api\Factory;

use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Schema\Mapping;

/**
 * @package Katana\Sdk\Api\Factory
 */
class MiddlewareApiFactory extends ApiFactory
{
    /**
     * Build a Request Api class instance
     *
     * @param string $action
     * @param array $data
     * @param CliInput $input
     * @param Mapping $mapping
     * @return Api
     */
    public function build(
        $action,
        array $data,
        CliInput $input,
        Mapping $mapping
    )
    {
        if ($action === 'request') {
            return new RequestApi(
                $this->logger,
                $this->component,
                $mapping,
                dirname(realpath($_SERVER['SCRIPT_FILENAME'])),
                $input->getName(),
                $input->getVersion(),
                $input->getFrameworkVersion(),
                $input->getVariables(),
                $input->isDebug(),
                $this->mapper->getHttpRequest($data),
                $this->mapper->getServiceCall($data),
                $this->mapper->getGatewayProtocol($data),
                $this->mapper->getGatewayAddress($data),
                $this->mapper->getClientAddress($data)
            );

        } elseif($action === 'response') {
            return new ResponseApi(
                $this->logger,
                $this->component,
                $mapping,
                dirname(realpath($_SERVER['SCRIPT_FILENAME'])),
                $input->getName(),
                $input->getVersion(),
                $input->getFrameworkVersion(),
                $input->getVariables(),
                $input->isDebug(),
                $this->mapper->getHttpRequest($data),
                $this->mapper->getHttpResponse($data),
                $this->mapper->getTransport($data),
                $this->mapper->getGatewayProtocol($data),
                $this->mapper->getGatewayAddress($data),
                $this->mapper->getReturnValue($data)
            );
        }

    }
}

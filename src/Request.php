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

namespace Katana\Sdk;

use Katana\Sdk\Api\ApiInterface;
use Katana\Sdk\Api\ParamContainerInterface;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;

interface Request extends ApiInterface, ParamContainerInterface
{
    /**
     * @return string
     */
    public function getServiceName(): string;

    /**
     * @param string $service
     * @return Request
     */
    public function setServiceName(string $service): Request;

    /**
     * @return string
     */
    public function getServiceVersion(): string;

    /**
     * @param string $version
     * @return Request
     */
    public function setServiceVersion(string $version): Request;

    /**
     * @return string
     */
    public function getActionName(): string;

    /**
     * @param string $action
     * @return Request
     */
    public function setActionName(string $action): Request;

    /**
     * @param int $code
     * @param string $text
     * @return Response
     */
    public function newResponse(int $code = 200, string $text = 'OK'): Response;

    /**
     * @return HttpRequest
     */
    public function getHttpRequest(): HttpRequest;

    /**
     * @return string
     */
    public function getGatewayProtocol(): string;

    /**
     * @return string
     */
    public function getGatewayAddress(): string;

    /**
     * @return string
     */
    public function getClientAddress(): string;

    /**
     * @return string
     */
    public function getId():string;

    /**
     * @return string
     */
    public function getTimestamp(): string;

    /**
     * @param string $name
     * @param string $value
     * @return Request
     */
    public function setAttribute(string $name, string $value): Request;
}

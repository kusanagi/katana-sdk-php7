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

namespace Katana\Sdk\Api;

use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Protocol\Http\HttpStatus;
use Katana\Sdk\Api\Value\PayloadMeta;
use Katana\Sdk\Api\Value\ReturnValue;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Request;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Response;
use Katana\Sdk\Param as ParamInterface;

class RequestApi extends Api implements Request
{
    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ServiceCall
     */
    private $call;

    /**
     * @var PayloadMeta
     */
    private $payloadMeta;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * Response constructor.
     * @param KatanaLogger $logger
     * @param Component $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpRequest $httpRequest
     * @param ServiceCall $call
     * @param PayloadMeta $payloadMeta
     * @param array $attributes
     */
    public function __construct(
        KatanaLogger $logger,
        Component $component,
        Mapping $mapping,
        string $path,
        string $name,
        string $version,
        string $frameworkVersion,
        array $variables,
        bool $debug,
        HttpRequest $httpRequest,
        ServiceCall $call,
        PayloadMeta $payloadMeta,
        array $attributes = []
    ) {
        parent::__construct(
            $logger,
            $component,
            $mapping,
            $path,
            $name,
            $version,
            $frameworkVersion,
            $variables,
            $debug
        );
        $this->httpRequest = $httpRequest;
        $this->call = $call;
        $this->payloadMeta = $payloadMeta;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->call->getService();
    }

    /**
     * @param string $service
     * @return Request
     */
    public function setServiceName(string $service): Request
    {
        $this->call->setService($service);

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceVersion(): string
    {
        return $this->call->getVersion();
    }

    /**
     * @param string $version
     * @return Request
     */
    public function setServiceVersion(string $version): Request
    {
        $this->call->setVersion(new VersionString($version));

        return $this;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->call->getAction();
    }

    /**
     * @param string $action
     * @return Request
     */
    public function setActionName(string $action): Request
    {
        $this->call->setAction($action);

        return $this;
    }

    /**
     * @param int $code
     * @param string $text
     * @return Response
     */
    public function newResponse(int $code = 200, string $text = 'OK'): Response
    {
        return new ResponseApi(
            $this->logger,
            $this->component,
            $this->mapping,
            $this->path,
            $this->name,
            $this->version,
            $this->frameworkVersion,
            $this->variables,
            $this->debug,
            $this->httpRequest,
            new HttpResponse(
                $this->httpRequest->getProtocolVersion(),
                new HttpStatus($code, $text),
                ''
            ),
            Transport::newEmpty(),
            $this->payloadMeta,
            new ReturnValue()
        );
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest(): HttpRequest
    {
        return $this->httpRequest;
    }

    /**
     * @return ServiceCall
     */
    public function getServiceCall(): ServiceCall
    {
        return $this->call;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->payloadMeta->getId();
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->payloadMeta->getTimestamp();
    }

    /**
     * @return string
     */
    public function getGatewayProtocol(): string
    {
        return $this->payloadMeta->getGatewayProtocol();
    }

    /**
     * @return string
     */
    public function getGatewayAddress(): string
    {
        return $this->payloadMeta->getGatewayAddress();
    }

    /**
     * @return string
     */
    public function getClientAddress(): string
    {
        return $this->payloadMeta->getClientAddress();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam(string $name): bool
    {
        return $this->call->hasParam($name);
    }

    /**
     * @param string $name
     * @return ParamInterface
     */
    public function getParam(string $name): ParamInterface
    {
        return $this->call->getParam($name);
    }

    /**
     * @return ParamInterface[]
     */
    public function getParams(): array
    {
        return $this->call->getParams();
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @return ParamInterface
     */
    public function newParam(
        string $name,
        $value = '',
        $type = Param::TYPE_STRING
    ): ParamInterface {
        return $this->call->newParam($name, $value, $type);
    }

    /**
     * @param ParamInterface $param
     * @return ParamContainerInterface
     */
    public function setParam(ParamInterface $param): ParamContainerInterface
    {
        $this->call->setParam($param);

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Request
     */
    public function setAttribute(string $name, string $value): Request
    {
        $this->attributes[$name] = $value;

        return $this;
    }
}

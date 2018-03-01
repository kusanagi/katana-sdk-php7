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

namespace Katana\Sdk\Api;

use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Value\PayloadMeta;
use Katana\Sdk\Api\Value\ReturnValue;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Logger\RequestKatanaLogger;
use Katana\Sdk\Response;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Transport as TransportInterface;

class ResponseApi extends Api implements Response
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var HttpResponse
     */
    private $response;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var PayloadMeta
     */
    private $payloadMeta;

    /**
     * @var ReturnValue
     */
    private $return;

    /**
     * Response constructor.
     * @param RequestKatanaLogger $logger
     * @param Component $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpRequest $request
     * @param HttpResponse $response
     * @param Transport $transport
     * @param PayloadMeta $payloadMeta
     * @param ReturnValue $return
     */
    public function __construct(
        RequestKatanaLogger $logger,
        Component $component,
        Mapping $mapping,
        $path,
        $name,
        $version,
        $frameworkVersion,
        array $variables,
        $debug,
        HttpRequest $request,
        HttpResponse $response,
        Transport $transport,
        PayloadMeta $payloadMeta,
        ReturnValue $return
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
        $this->request = $request;
        $this->response = $response;
        $this->transport = $transport;
        $this->payloadMeta = $payloadMeta;
        $this->return = $return;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest(): HttpRequest
    {
        return $this->request;
    }

    /**
     * @return HttpResponse
     */
    public function getHttpResponse(): HttpResponse
    {
        return $this->response;
    }

    /**
     * @return TransportInterface
     */
    public function getTransport()
    {
        return new TransportReader($this->transport);
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
     * @return bool
     */
    public function hasReturn(): bool
    {
        return $this->return->exists();
    }

    /**
     * @return mixed
     * @throws InvalidValueException
     */
    public function getReturn()
    {
        try {
            return $this->return->getValue();
        } catch (InvalidValueException $e) {
            list ($service, $version, $action) = $this->getTransport()->getOriginService();
            throw new InvalidValueException(sprintf(
                'No return value defined on "%s" (%s) for action: "%s"',
                $service,
                $version,
                $action
            ));
        }
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getRequestAttribute(string $name, string $default = ''): string
    {
        return $this->payloadMeta->getAttribute($name, $default);
    }

    /**
     * @return array
     */
    public function getRequestAttributes(): array
    {
        return $this->payloadMeta->getAttributes();
    }
}

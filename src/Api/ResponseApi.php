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
use Katana\Sdk\Component\Component;
use Katana\Sdk\Logger\KatanaLogger;
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
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $gatewayAddress;

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
     * @param HttpRequest $request
     * @param HttpResponse $response
     * @param Transport $transport
     * @param string $protocol
     * @param string $gatewayAddress
     */
    public function __construct(
        KatanaLogger $logger,
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
        $protocol,
        $gatewayAddress
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
        $this->protocol = $protocol;
        $this->gatewayAddress = $gatewayAddress;
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
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getGatewayAddress(): string
    {
        return $this->gatewayAddress;
    }
}

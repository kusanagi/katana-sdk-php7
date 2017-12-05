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

namespace Katana\Sdk\Api\Mapper;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Protocol\Http\HttpStatus;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Api\ServiceCall;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\Value\PayloadMeta;
use Katana\Sdk\Api\Value\ReturnValue;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Mapper\CompactTransportMapper;
use Katana\Sdk\Mapper\TransportWriterInterface;

class CompactPayloadMapper implements PayloadMapperInterface
{
    /**
     * @var TransportWriterInterface
     */
    private $transportMapper;

    public function __construct()
    {
        $this->transportMapper = new CompactTransportMapper();
    }

    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['n'],
            $param['v'],
            $param['t'],
            true
        );
    }

    /**
     * @param Param $param
     * @return array
     */
    private function writeParam(Param $param)
    {
        return [
            'n' => $param->getName(),
            'v' => $param->getValue(),
            't' => $param->getType(),
        ];
    }

    /**
     * @param array $raw
     * @return Param[]
     */
    public function getParams(array $raw)
    {
        if (empty($raw['c']['a']['p'])) {
            return [];
        }

        $return = [];
        foreach ($raw['c']['a']['p'] as $param) {
            $return[] = new Param(
                $param['n'],
                $param['v'],
                $param['t'],
                true
            );
        }

        return $return;
    }

    /**
     * @param ActionApi $action
     * @return array
     */
    public function writeActionResponse(ActionApi $action)
    {
        $response = [
            'cr' => [
                'n' => $action->getName(),
            ],
        ];

        if ($action->hasReturn()) {
            $response['cr']['r']['rv'] = $action->getReturn();
        }

        return $this->writeTransport($action->getTransport(), $response);
    }

    /**
     * @param RequestApi $request
     * @return array
     */
    public function writeRequestResponse(RequestApi $request)
    {
        $message = [
            'cr' => [
                'n' => $request->getName(),
            ],
        ];

        $message['cr']['r']['a'] = $request->getAttributes();

        return $this->writeServiceCall($request->getServiceCall(), $message);
    }

    /**
     * @param ResponseApi $response
     * @return array
     */
    public function writeResponseResponse(ResponseApi $response)
    {
        $message = [
            'cr' => [
                'n' => $response->getName(),
            ],
        ];

        return $this->writeHttpResponse($response->getHttpResponse(), $message);
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return array
     */
    public function writeErrorResponse($message = '', $code = 0, $status = '')
    {
        $error = [];
        if ($message) {
            $error['m'] = $message;
        }

        if ($code) {
            $error['c'] = $code;
        }

        if ($status) {
            $error['s'] = $status;
        }

        return ['E' => $error];
    }

    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw)
    {
        return $this->transportMapper->getTransport($raw['c']['a']['T']);
    }

    /**
     * @param Transport $transport
     * @param array $output
     * @return array
     */
    public function writeTransport(Transport $transport, array $output)
    {
        $transport =  $this->transportMapper->writeTransport($transport);
        $output['cr']['r']['T'] = $transport;

        return $output;
    }

    /**
     * @param array $raw
     * @return HttpRequest
     */
    public function getHttpRequest(array $raw)
    {
        $query = isset($raw['c']['a']['r']['q'])?
            $raw['c']['a']['r']['q'] : [];
        $postData = isset($raw['c']['a']['r']['p'])?
            $raw['c']['a']['r']['p'] : [];
        $headers = isset($raw['c']['a']['r']['h'])?
            $raw['c']['a']['r']['h'] : [];
        $body = isset($raw['c']['a']['r']['b'])?
            $raw['c']['a']['r']['b'] : '';

        $rawFiles = isset($raw['c']['a']['r']['f'])?
            $raw['c']['a']['r']['f'] : [];
        $files = array_map(function (array $fileData) {
            return new File(
                $fileData['n'],
                $fileData['p'],
                $fileData['m'],
                $fileData['f'],
                $fileData['s'],
                $fileData['t']
            );
        }, $rawFiles);

        return new HttpRequest(
            $raw['c']['a']['r']['v'],
            $raw['c']['a']['r']['m'],
            $raw['c']['a']['r']['u'],
            $query,
            $postData,
            $headers,
            $body,
            $files
        );
    }

    /**
     * @param array $raw
     * @return PayloadMeta
     */
    public function getPayloadMeta(array $raw): PayloadMeta
    {
        return new PayloadMeta(
            $raw['c']['a']['m']['i'],
            $raw['c']['a']['m']['d'],
            $raw['c']['a']['m']['p'],
            $raw['c']['a']['m']['g'][1],
            $raw['c']['a']['m']['c'],
            $raw['c']['a']['m']['a'] ?? []
        );
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getReturnValue(array $raw)
    {
        if (isset($raw['c']['a']['rv'])) {
            return new ReturnValue($raw['c']['a']['rv'], true);
        } else {
            return new ReturnValue();
        }
    }

    /**
     * @param array $raw
     * @return HttpResponse
     */
    public function getHttpResponse(array $raw)
    {
        list($statusCode, $statusText) = explode(' ', $raw['c']['a']['R']['s'], 2);

        $headers = isset($raw['c']['a']['R']['h'])?
            $raw['c']['a']['R']['h'] : [];

        $headers = array_map(function ($header) {
            return (array) $header;
        }, $headers);

        return new HttpResponse(
            $raw['c']['a']['R']['v'],
            new HttpStatus($statusCode, $statusText),
            $raw['c']['a']['R']['b'] ?? '',
            $headers
        );
    }

    /**
     * @param array $raw
     * @return ServiceCall
     */
    public function getServiceCall(array $raw)
    {
        $params = [];
        if (isset($raw['c']['a']['c']['p'])) {
            foreach ($raw['c']['a']['c']['p'] as $param) {
                $params[] = $this->getParam($param);
            }
        }

        return new ServiceCall(
            $raw['c']['a']['c']['s'],
            new VersionString($raw['c']['a']['c']['v']),
            $raw['c']['a']['c']['a'],
            $params
        );
    }

    /**
     * @param ServiceCall $call
     * @param array $output
     * @return array
     */
    public function writeServiceCall(ServiceCall $call, array $output)
    {
        $output['cr']['r']['c'] = [
            's' => $call->getService(),
            'v' => $call->getVersion(),
            'a' => $call->getAction(),
            'p' => array_map([$this, 'writeParam'], array_values($call->getParams())),
        ];

        return $output;
    }

    /**
     * @param array $raw
     * @return array
     */
    public function getRequestAttributes(array $raw): array
    {
        return $raw['c']['a']['a'] ?? [];
    }

    /**
     * @param HttpResponse $response
     * @param array $output
     * @return array
     */
    public function writeHttpResponse(HttpResponse $response, array $output)
    {
        $output['cr']['r']['R'] = [
            'v' => $response->getProtocolVersion(),
            's' => $response->getStatus(),
            'b' => $response->getBody(),
        ];

        if ($response->getHeaders()) {
            $output['cr']['r']['R']['h'] = $response->getHeadersArray();
        }

        return $output;
    }
}

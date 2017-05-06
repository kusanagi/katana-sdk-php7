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
use Katana\Sdk\Api\DeferCall;
use Katana\Sdk\Api\Value\ReturnValue;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Api\Error;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Protocol\Http\HttpStatus;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Api\ServiceCall;
use Katana\Sdk\Api\ServiceOrigin;
use Katana\Sdk\Api\Transaction;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\TransportCalls;
use Katana\Sdk\Api\TransportData;
use Katana\Sdk\Api\TransportErrors;
use Katana\Sdk\Api\TransportFiles;
use Katana\Sdk\Api\TransportLinks;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Api\TransportRelations;
use Katana\Sdk\Api\TransportTransactions;
use Katana\Sdk\Mapper\ExtendedTransportMapper;

class ExtendedPayloadMapper implements PayloadMapperInterface
{
    /**
     * @var ExtendedTransportMapper
     */
    private $transportMapper;

    public function __construct()
    {
        $this->transportMapper = new ExtendedTransportMapper();
    }
    /**
     * @param array $param
     * @return Param
     */
    private function getParam(array $param)
    {
        return new Param(
            $param['name'],
            $param['value'],
            $param['type'],
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
            'name' => $param->getName(),
            'version' => $param->getValue(),
            'type' => $param->getType(),
        ];
    }

    /**
     * @param array $raw
     * @return Param[]
     */
    public function getParams(array $raw)
    {
        if (empty($raw['command']['arguments']['params'])) {
            return [];
        }

        $return = [];
        foreach ($raw['command']['arguments']['params'] as $param) {
            $return[] = new Param(
                $param['name'],
                $param['value'],
                $param['type'],
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
            'command_reply' => [
                'name' => 'test',
            ],
        ];

        if ($action->hasReturn()) {
            $response['command_reply']['result']['return'] = $action->getReturn();
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
            'command_reply' => [
                'name' => 'test',
            ],
        ];

        return $this->writeServiceCall($request->getServiceCall(), $message);
    }

    /**
     * @param ResponseApi $response
     * @return array
     */
    public function writeResponseResponse(ResponseApi $response)
    {
        $message = [
            'command_reply' => [
                'name' => 'test',
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
            $error['message'] = $message;
        }

        if ($code) {
            $error['code'] = $code;
        }

        if ($status) {
            $error['status'] = $status;
        }

        return ['error' => $error];
    }

    /**
     * @param array $raw
     * @return Transport
     */
    public function getTransport(array $raw)
    {
        return $this->transportMapper->getTransport($raw);
    }

    /**
     * @param Transport $transport
     * @param array $output
     * @return array
     */
    public function writeTransport(Transport $transport, array $output)
    {
        $transport = $this->transportMapper->writeTransport($transport);
        $output['command_reply']['result']['transport'] = $transport;

        return $output;
    }

    /**
     * @param array $raw
     * @return HttpRequest
     */
    public function getHttpRequest(array $raw)
    {
        $query = isset($raw['command']['arguments']['request']['query'])?
            $raw['command']['arguments']['request']['query'] : [];
        $postData = isset($raw['command']['arguments']['request']['post data'])?
            $raw['command']['arguments']['request']['post data'] : [];
        $headers = isset($raw['command']['arguments']['request']['headers'])?
            $raw['command']['arguments']['request']['headers'] : [];
        $body = isset($raw['command']['arguments']['request']['body'])?
            $raw['command']['arguments']['request']['body'] : '';

        $rawFiles = isset($raw['command']['arguments']['request']['files'])?
            $raw['command']['arguments']['request']['files'] : [];
        $files = array_map(function (array $fileData) {
            return new File(
                $fileData['name'],
                $fileData['path'],
                $fileData['mime'],
                $fileData['filename'],
                $fileData['size'],
                $fileData['token']
            );
        }, $rawFiles);

        return new HttpRequest(
            $raw['command']['arguments']['request']['version'],
            $raw['command']['arguments']['request']['method'],
            $raw['command']['arguments']['request']['url'],
            $query,
            $postData,
            $headers,
            $body,
            $files
        );
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayProtocol(array $raw)
    {
        return $raw['command']['arguments']['meta']['protocol'];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getGatewayAddress(array $raw)
    {
        return $raw['command']['arguments']['meta']['gateway'][1];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getClientAddress(array $raw)
    {
        return $raw['command']['arguments']['meta']['client'];
    }

    /**
     * @param array $raw
     * @return string
     */
    public function getReturnValue(array $raw)
    {
        if (isset($raw['command']['arguments']['return'])) {
            return new ReturnValue($raw['command']['arguments']['return'], true);
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
        list($statusCode, $statusText) = explode(' ', $raw['command']['arguments']['response']['status'], 2);

        $headers = isset($raw['command']['arguments']['response']['headers'])?
            $raw['command']['arguments']['response']['headers'] : [];

        return new HttpResponse(
            $raw['command']['arguments']['response']['version'],
            new HttpStatus($statusCode, $statusText),
            $raw['command']['arguments']['response']['body'],
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
        if (isset($raw['command']['arguments']['call']['params'])) {
            foreach ($raw['command']['arguments']['call']['params'] as $param) {
                $params[] = $this->getParam($param);
            }
        }

        return new ServiceCall(
            $raw['command']['arguments']['call']['service'],
            new VersionString($raw['command']['arguments']['call']['version']),
            $raw['command']['arguments']['call']['action'],
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
        $output['command_reply']['response']['call'] = [
            'service' => $call->getService(),
            'version' => $call->getVersion(),
            'action' => $call->getAction(),
            'params' => array_map([$this, 'writeParam'], $call->getParams()),
        ];

        return $output;
    }

    /**
     * @param HttpResponse $response
     * @param array $output
     * @return array
     */
    public function writeHttpResponse(HttpResponse $response, array $output)
    {
        $output['command_reply']['response']['response'] = [
            'version' => $response->getProtocolVersion(),
            'status' => $response->getStatus(),
            'body' => $response->getBody(),
        ];

        if ($response->getHeaders()) {
            $output['command_reply']['response']['response']['headers'] = $response->getHeaders();
        }

        return $output;
    }
}

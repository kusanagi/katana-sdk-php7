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

require __DIR__ . '/../../../vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();
$middleware->request(function (\Katana\Sdk\Request $request) {

    $httpRequest = $request->getHttpRequest();

    $request->setServiceName('test');
    $request->setServiceVersion('0.1.0');
    $request->setActionName('read');

    return $request;
});

$middleware->response(function (\Katana\Sdk\Response $response) {

    $httpRequest = $response->getHttpRequest();
    $httpResponse = $response->getHttpResponse();

    var_dump($response->getMethod());

    $httpResponse->setHeader('Content-Type', 'application/vnd.api+json');
    $statusCode = $httpResponse->getStatusCode();

    if ($statusCode > 500) {
        $httpResponse->setBody(json_encode([
            'errors' => [
                [
                    'detail' => 'Internal Error',
                    'code' => '500',
                    'status' => '500 Internal Error'
                ]
            ]
        ]));

        $httpResponse->setStatus(500, 'Internal error');

        return $response;
    }

    $transport = $response->getTransport();
    $transportErrors = $transport->getErrors();

    if ($transportErrors) {
        $errors = [];

        foreach ($transportErrors as $serviceErrors) {
            foreach ($serviceErrors as $versionErrors) {
                foreach ($versionErrors as $error) {
                    $bodyError = [
                        'detail' => $error['m'],
                    ];

                    if ($error['c']) {
                        $bodyError['code'] = $error['c'];
                    }

                    if ($error['s']) {
                        $bodyError['status'] = $error['s'];
                    }

                    $errors[] = $bodyError;

                    if (!$httpResponse->getStatusCode()) {
                        $httpResponse->setStatus($error['c'], $error['s']);
                    }
                }
            }
        }
        $httpResponse->setBody(json_encode($errors));

        return $response;
    }

    list($service, $version) = $transport->getOrigin();
    $data = $transport->getData($service, $version);
    foreach ($data as $actionData) {
        $httpResponse->setBody(json_encode([
            'data' => $actionData,
        ]));
    }

    $httpResponse->setStatus(200, 'OK');

    return $response;
});

$middleware->run();
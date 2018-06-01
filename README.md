KATANA SDK for PHP7
=========================

[![Build Status](https://travis-ci.org/kusanagi/katana-sdk-php7.svg?branch=master)](https://travis-ci.org/kusanagi/katana-sdk-php7)
[![Coverage Status](https://coveralls.io/repos/github/kusanagi/katana-sdk-php7/badge.svg?branch=master)](https://coveralls.io/github/kusanagi/katana-sdk-php7?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

PHP7 SDK to interface with the **KATANA**™ framework (https://kusanagi.io).

Requirements
------------

* KATANA Framework 2.1
* [libzmq](http://zeromq.org) 4.1.5+
* [zmq extension](https://github.com/mkoppanen/php-zmq)
* [msgpack extension](https://github.com/msgpack/msgpack-php)

Installation
------------

The PHP7 SDK can be installed using [composer](https://getcomposer.org/).

```
composer require kusanagi/katana-sdk-php7
```

Getting Started
---------------

The SDK allow both **Services** and **Middlewares** to be created. Both of them require a source file and a configuration file pointing to it.

The first step to create a **Service** or a **Middleware** is to generate the configuration file, which will define the behavior of the component. In both cases the configuration include name and version of the component and the engine and source file to run it.

The configuration file for a **Service** define the different actions it can respond to, and can include http configuration so it can interact with a **Gateway**.

```yaml
"@context": urn:katana:service
name: service_name
version: "0.1"
http-base-path: /0.1
info:
  title: Example Service
engine:
  runner: urn:katana:runner:php7
  path: ./example_service.php
action:
  - name: action_name
    http-path: /action/path
```

The configuration of a **Middleware** defines which kind of action (*request*, *response* or both), it responds to.

```yaml
"@context": urn:katana:middleware
name: middleware_name
version: "0.1"
request: true
response: true
info:
  title: Example Middleware
engine:
  runner: urn:katana:runner:php7
  path: ./example_middleware.php
```

The following example illustrates how to create a **Service**. Given the previous configuration file, the source file must be located at `./example_service.php`, define the actions and run the component:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$service = new \Katana\Sdk\Service();

$service->action('action_name', function (\Katana\Sdk\Action $action) {
    $action->log('Start action');

    return $action;
});

$service->run();
```

The following example illustrates how to create a request **middleware**. Given the previous configuration file, the source file must be located at `./example_middleware.php`, define the *request* and *response* and run the component:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->log('Start Request');

    return $request;
});

$middleware->response(function (\Katana\Sdk\Response $request) {
    $request->log('Start Response');

    return $request;
});

$middleware->run();
```

Examples
--------

One common responsibility of the request **Middlewares** is routing request to the **Service** actions. For this the **Middleware** should set the target **Service**, version and action.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->setServiceName('service');
    $request->setServiceVersion('1.0.0');
    $request->setActionName('action');

    return $request;
});
```

Response **Middleware** commonly format the data in the transport to present a response.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->response(function (\Katana\Sdk\Response $response) {
    $httpResponse = $response->getHttpResponse();
    $httpResponse->setBody(
        json_encode(
            $response->getTransport()->getData()
        )
    );
    $httpResponse->setStatus(200, 'OK');

    return $response;
});
```

A **Service** can be used to group some related functionality, like a CRUD for a business model.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$service = new \Katana\Sdk\Service();

$service->action('read', function (\Katana\Sdk\Action $action) {
    $entity = $repository->get($action->getParam('id')->getValue());
    $action->setEntity($entity);

    return $action;
});

$service->action('delete', function (\Katana\Sdk\Action $action) {
    $entity = $repository->delete($action->getParam('id')->getValue());

    return $action;
});

$service->action('create', function (\Katana\Sdk\Action $action) {
    $repository->create(array_map(function (\Katana\Sdk\Param $param) {
        return $param->getValue();
    }, $action->getParams()));

    return $action;
});

$service->action('update', function (\Katana\Sdk\Action $action) {
    $repository->update(array_map(function (\Katana\Sdk\Param $param) {
        return $param->getValue();
    }, $action->getParams()));

    return $action;
});

$service->run();
```

Documentation
-------------

See the [API](https://app.kusanagi.io#katana/docs/sdk) for a technical reference of the SDK.

For help using the framework see the [documentation](https://app.kusanagi.io#katana/docs).

Support
-------

Please first read our [contribution guidelines](https://app.kusanagi.io#katana/open-source/contributing).

* [Requesting help](https://app.kusanagi.io#katana/open-source/help)
* [Reporting a bug](https://app.kusanagi.io#katana/open-source/bug)
* [Submitting a patch](https://app.kusanagi.io#katana/open-source/patch)
* [Security issues](https://app.kusanagi.io#katana/open-source/security)

We use [milestones](https://github.com/kusanagi/katana-sdk-php7/milestones) to track upcoming releases inline with our [versioning](https://app.kusanagi.io#katana/docs/framework/versions) strategy, and as defined in our [roadmap](https://app.kusanagi.io#katana/docs/framework/roadmap).

For commercial support see the [solutions](https://kusanagi.io/solutions) available or [contact us](https://kusanagi.io/contact) for more information.

Contributing
------------

If you'd like to know how you can help and support our Open Source efforts see the many ways to [get involved](https://app.kusanagi.io#katana/open-source).

Please also be sure to review our [community guidelines](https://app.kusanagi.io#katana/open-source/conduct).

License
-------

Copyright 2016-2018 KUSANAGI S.L. (https://kusanagi.io). All rights reserved.

KUSANAGI, the sword logo, KATANA and the "K" logo are trademarks and/or registered trademarks of KUSANAGI S.L. All other trademarks are property of their respective owners.

Licensed under the [MIT License](https://app.kusanagi.io#katana/open-source/license). Redistributions of the source code included in this repository must retain the copyright notice found in each file.

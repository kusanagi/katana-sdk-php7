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

namespace Katana\Sdk\Executor;

use Exception;
use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\Responder\ResponderInterface;
use Throwable;

/**
 * Base class for component executors
 *
 * @package Katana\Sdk\Executor
 */
abstract class AbstractExecutor
{
    /**
     * @var ResponderInterface
     */
    protected $responder;

    /**
     * @var PayloadWriterInterface
     */
    protected $mapper;

    /**
     * @var KatanaLogger
     */
    protected $logger;

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     */
    protected function sendError($message, $code = 0, $status = '')
    {
        $message = "Callback error: $message";
        if ($message) {
            $this->logger->error($message);
        }

        $this->responder->sendErrorResponse($this->mapper, $message, $code, $status);
    }

    /**
     * @param Api $api
     * @param string $action
     * @param array $callbacks
     * @param callable|null $errorCallback
     * @return bool
     */
    protected function executeCallback(
        Api $api,
        $action,
        array $callbacks,
        callable $errorCallback = null
    ) {
        try {
            $response = $callbacks[$action]($api);
        } catch (Throwable $response) {
            // Catch as response to allow return or throw Exception
        }

        if ($response instanceof Api) {
            $this->responder->sendResponse($response, $this->mapper);

            return true;
        }

        if (!$response instanceof Throwable) {
            $response = new Exception('Wrong return from callback');
        }

        if ($errorCallback) {
            $errorCallback($response);
        }

        $this->sendError($response->getMessage());

        return false;
    }

    /**
     * @param ResponderInterface $responder
     * @param PayloadWriterInterface $mapper
     * @param KatanaLogger $logger
     */
    public function __construct(
        ResponderInterface $responder,
        PayloadWriterInterface $mapper,
        KatanaLogger $logger
    ) {
        $this->responder = $responder;
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     * @param callable $errorCallback
     */
    abstract public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks,
        callable $errorCallback = null
    );
}

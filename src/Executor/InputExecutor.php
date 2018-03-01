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

namespace Katana\Sdk\Executor;

use Exception;
use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\Responder\ResponderInterface;
use Katana\Sdk\Schema\Mapping;

/**
 * Executor that gets a single input from cli
 *
 * @package Katana\Sdk\Executor
 */
class InputExecutor extends AbstractExecutor
{
    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @param ResponderInterface $responder
     * @param PayloadWriterInterface $mapper
     * @param KatanaLogger $logger
     * @param Mapping $mapping
     */
    public function __construct(
        ResponderInterface $responder,
        PayloadWriterInterface $mapper,
        KatanaLogger $logger,
        Mapping $mapping
    ) {
        $this->mapping = $mapping;
        parent::__construct($responder, $mapper, $logger);
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     * @param callable $errorCallback
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks,
        callable $errorCallback = null
    ) {
        $command = json_decode($input->getInput(), true);

        $action = $input->getAction();
        if (!isset($callbacks[$action])) {
            return $this->sendError("Unregistered callback {$input->getAction()}");
        }

        $api = $factory->build($action, $command, $input, $this->mapping);
        $this->executeCallback($api, $action, $callbacks, $errorCallback);
    }
}

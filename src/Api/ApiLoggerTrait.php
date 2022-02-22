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

use Closure;
use Katana\Sdk\Logger\KatanaLogger;

trait ApiLoggerTrait
{
    /**
     * @return KatanaLogger
     */
    abstract public function getLogger(): KatanaLogger;

    /**
     * @param mixed $value
     * @return bool
     */
    public function log($value): bool
    {
        if ($this->logger->getLevel() !== KatanaLogger::LOG_DEBUG) {
            return false;
        }

        if (is_null($value)) {
            $log = 'NULL';
        } elseif (is_string($value)) {
            $log = $value;
        } elseif (is_callable($value)) {
            if ($value instanceof Closure) {
                $log = 'function anonymous';
            } elseif (is_array($value)) {
                list($class, $method) = $value;
                if (is_object($class)) {
                    $class = get_class($class);
                }
                $log = "function $class::$method";
            } else {
                $log = 'Unknown value type';
            }
        } elseif (is_bool($value)) {
            $log = $value? 'TRUE' : 'FALSE';
        } elseif (is_float($value)) {
            $log = rtrim(sprintf('%.9f', $value));
        } elseif (is_array($value)) {
            $log = json_encode($value);
        } elseif (is_int($value) || is_resource($value)) {
            $log = (string) $value;
        } else {
            $log = 'Unknown value type';
        }

        $this->logger->debug(substr($log, 0, 100000));

        return true;
    }
}

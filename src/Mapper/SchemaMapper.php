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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Schema\ActionEntity;
use Katana\Sdk\Schema\ActionRelation;
use Katana\Sdk\Schema\ActionReturn;
use Katana\Sdk\Schema\ActionSchema;
use Katana\Sdk\Schema\FileSchema;
use Katana\Sdk\Schema\FileValidation;
use Katana\Sdk\Schema\ParamExpectation;
use Katana\Sdk\Schema\ParamSchema;
use Katana\Sdk\Schema\ParamType;
use Katana\Sdk\Schema\ParamValidation;
use Katana\Sdk\Schema\Protocol\HttpActionSchema;
use Katana\Sdk\Schema\Protocol\HttpFileSchema;
use Katana\Sdk\Schema\Protocol\HttpParamSchema;
use Katana\Sdk\Schema\Protocol\HttpServiceSchema;
use Katana\Sdk\Schema\ServiceSchema;

class SchemaMapper
{
    /**
     * @param array $source
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    private function read(array $source, $path, $default = null)
    {
        if (strpos($path, '.') !== false) {
            list($key, $rest) = explode('.', $path, 2);

            if (isset($source[$key])) {
                return $this->read($source[$key], $rest, $default);
            } else {
                return $default;
            }
        }

        $key = $path;
        if (isset($source[$key])) {
            return $source[$key];
        } else {
            return $default;
        }


    }
    
    /**
     * @param string $name
     * @param string $version
     * @param array $raw
     * @return ServiceSchema
     */
    public function getServiceSchema($name, $version, array $raw)
    {
        $http = new HttpServiceSchema(
            $this->read($raw, 'h.g', true),
            $this->read($raw, 'h.b', '')
        );

        $actions = [];
        foreach ($this->read($raw, 'ac', []) as $actionName => $action) {
            $params = [];
            foreach ($this->read($action, 'p', []) as $paramName => $param) {
                $params[] = new ParamSchema(
                    $paramName,
                    new HttpParamSchema(
                        $this->read($param, 'h.g', true),
                        $this->read($param, 'h.i', 'query'),
                        $this->read($param, 'h.p', $paramName)
                    ),
                    new ParamType(
                        $this->read($param, 't', 'string'),
                        $this->read($param, 'f', ''),
                        $this->read($param, 'af', 'csv'),
                        $this->read($param, 'i', '')
                    ),
                    new ParamExpectation(
                        $this->read($param, 'd', null),
                        $this->read($param, 'r', false),
                        $this->read($param, 'e', false)
                    ),
                    new ParamValidation(
                        $this->read($param, 'p', ''),
                        $this->read($param, 'mx', PHP_INT_MAX),
                        $this->read($param, 'ex', false),
                        $this->read($param, 'mn', ~PHP_INT_MAX),
                        $this->read($param, 'en', false),
                        $this->read($param, 'xl', -1),
                        $this->read($param, 'nl', -1),
                        $this->read($param, 'xi', -1),
                        $this->read($param, 'ni', -1),
                        $this->read($param, 'ui', false),
                        $this->read($param, 'em', []),
                        $this->read($param, 'mo', -1)
                    )
                );
            }

            $files = [];
            foreach ($this->read($action, 'f', []) as $fileName => $file) {
                $files[] = new FileSchema(
                    $fileName,
                    $this->read($file, 'm', 'text/plain'),
                    $this->read($file, 'r', false),
                    new HttpFileSchema(
                        $this->read($file, 'h.g', true),
                        $this->read($file, 'h.p', $fileName)
                    ),
                    new FileValidation(
                        $this->read($file, 'mx', PHP_INT_MAX),
                        $this->read($file, 'ex', false),
                        $this->read($file, 'mn', 0),
                        $this->read($file, 'en', false)
                    )
                );
            }

            $relations = [];
            foreach ($this->read($action, 'r', []) as $relation) {
                $relations[] = new ActionRelation(
                    $this->read($relation, 'n', ''),
                    $this->read($relation, 't', 'one')
                );
            }

            if ($this->read($action, 'rv')) {
                $return = new ActionReturn(
                    $this->read($action, 'rv.t'),
                    $this->read($action, 'rv.e', false)
                );
            } else {
                $return = null;
            }

            $actions[] = new ActionSchema(
                $actionName,
                new ActionEntity(
                    $this->read($action, 'e', ''),
                    $this->read($action, 'd', '/'),
                    $this->read($action, 'k', 'id'),
                    $this->read($action, 'c', false),
                    $this->read($action, 'E', [])
                ),
                new HttpActionSchema(
                    $this->read($action, 'h.g', true),
                    $this->read($action, 'h.p', '/'),
                    $this->read($action, 'h.m', 'get'),
                    $this->read($action, 'h.i', 'query'),
                    $this->read($action, 'h.b', 'text/plain')
                ),
                $this->read($action, 'D', false),
                $params,
                $files,
                $relations,
                $this->read($action, 'C', []),
                $this->read($action, 'dc', []),
                $this->read($action, 'rc', []),
                $this->read($action, 't', []),
                $return
            );
        }

        return new ServiceSchema(
            $name,
            $version,
            $this->read($raw, 'a', ''),
            $http,
            $actions,
            $this->read($raw, 'f', false)
        );
    }
}

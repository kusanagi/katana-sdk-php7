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

namespace Katana\Sdk\Api\Protocol\Http;

use Katana\Sdk\Api\File;

/**
 * Api class for an Http Request
 *
 * @package Katana\Sdk\Api
 */
class HttpRequest
{
    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var array
     */
    private $query = [];

    /**
     * @var array
     */
    private $postData = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $headerKeys = [];

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * @param string $version
     * @param string $method
     * @param string $url
     * @param array $query
     * @param array $postData
     * @param array $headers
     * @param string $body
     * @param File[] $files
     */
    public function __construct(
        string $version,
        string $method,
        string $url,
        array $query = [],
        array $postData = [],
        array $headers = [],
        string $body = '',
        array $files = []
    ) {
        $this->version = $version;
        $this->method = $method;
        $this->url = $url;
        $this->query = $query;
        $this->postData = $postData;
        $this->headers = array_combine(
            array_map('strtoupper', array_keys($headers)),
            array_values($headers)
        );
        $this->headerKeys = array_keys($headers);
        $this->body = $body;
        $this->files = $files;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->method === $method;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUrlScheme(): string
    {
        return parse_url($this->url, PHP_URL_SCHEME) ?: '';
    }

    /**
     * @return string
     */
    public function getUrlHost(): string
    {
        return parse_url($this->url, PHP_URL_HOST) ?: '';
    }

    /**
     * @return string
     */
    public function getUrlPath(): string
    {
        return parse_url($this->url, PHP_URL_PATH) ?: '';
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasQueryParam($name)
    {
        return isset($this->query[$name]);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getQueryParam($name, $default = '')
    {
        return isset($this->query[$name])? $this->query[$name][0] : $default;
    }

    /**
     * @param string $name
     * @param string[] $default
     * @return string[]
     */
    public function getQueryParamArray($name, array $default = [])
    {
        return isset($this->query[$name])? $this->query[$name] : $default;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return array_map(function (array $values) {
            return $values[0];
        }, $this->query);
    }

    /**
     * @return array
     */
    public function getQueryParamsArray()
    {
        return $this->query;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPostParam($name)
    {
        return isset($this->postData[$name]);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getPostParam($name, $default = '')
    {
        return isset($this->postData[$name])? $this->postData[$name][0] : $default;
    }

    /**
     * @param string $name
     * @param string[] $default
     * @return string[]
     */
    public function getPostParamArray($name, array $default = [])
    {
        return isset($this->postData[$name])? $this->postData[$name] : $default;
    }

    /**
     * @return array
     */
    public function getPostParams()
    {
        return array_map(function (array $values) {
            return $values[0];
        }, $this->postData);
    }

    /**
     * @return array
     */
    public function getPostParamsArray()
    {
        return $this->postData;
    }

    /**
     * @param string $version
     * @return bool
     */
    public function isProtocolVersion($version)
    {
        return $this->version === $version;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtoupper($name)]);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getHeader(string $name, string $default = ''): string
    {
        return $this->headers[strtoupper($name)][0] ?? $default;
    }

    /**
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getHeaderArray(string $name, array $default = []): array
    {
        return $this->headers[strtoupper($name)] ?? $default;
    }

    /**
     * @param array $arr
     * @return mixed
     */
    private function getFirst(array $arr)
    {
        return $arr[0];
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return array_combine(
            $this->headerKeys,
            array_values(array_map([$this, 'getFirst'], $this->headers))
        );
    }

    /**
     * @return array
     */
    public function getHeadersArray(): array
    {
        return array_combine(
            $this->headerKeys,
            array_values($this->headers)
        );
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return !empty($this->body);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile($name)
    {
        return isset($this->files[$name]);
    }

    /**
     * @param string $name
     * @return File
     */
    public function getFile($name)
    {
        return $this->hasFile($name)
            ? $this->files[$name]
            : new File($name, '');
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}

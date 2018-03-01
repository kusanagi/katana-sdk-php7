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

namespace Katana\Sdk\Api\Protocol\Http;

/**
 * Api class for an Http Response
 *
 * @package Katana\Sdk\Api
 */
class HttpResponse
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var HttpStatus
     */
    private $status;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $headerKeys = [];

    /**
     * @param string $version
     * @param HttpStatus $status
     * @param array $headers
     * @param string $body
     */
    public function __construct(
        $version,
        HttpStatus $status,
        $body,
        array $headers = []
    ) {
        $this->version = $version;
        $this->status = $status;
        $this->body = $body;
        $this->headers = array_combine(
            array_map('strtoupper', array_keys($headers)),
            array_values($headers)
        );
        $this->headerKeys = array_keys($headers);
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
     * @param string $version
     * @return HttpResponse
     */
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param string $status
     * @return bool
     */
    public function isStatus($status)
    {
        return $status === $this->status->get();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status->get();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status->getCode();
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return $this->status->getText();
    }

    /**
     * @param int $code
     * @param string $text
     * @return HttpResponse
     */
    public function setStatus($code, $text)
    {
        $this->status = new HttpStatus($code, $text);

        return $this;
    }

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header): bool
    {
        return isset($this->headers[strtoupper($header)]);
    }

    /**
     * @param string $header
     * @param string $default
     * @return string
     */
    public function getHeader($header, string $default = ''): string
    {
        return $this->headers[strtoupper($header)][0] ?? $default;
    }

    /**
     * @param string $header
     * @param array $default
     * @return array
     */
    public function getHeaderArray($header, array $default = []): array
    {
        return $this->headers[strtoupper($header)] ?? $default;
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
     * @param string $header
     * @param string $value
     * @return HttpResponse
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header][] = $value;
        $this->headerKeys[] = $header;

        return $this;
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
     * @param string $body
     * @return HttpResponse
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}

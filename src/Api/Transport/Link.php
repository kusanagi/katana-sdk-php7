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

namespace Katana\Sdk\Api\Transport;

class Link
{
    /**
     * @var string
     */
    private $address = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $link = '';

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @param string $address
     * @param string $name
     * @param string $link
     * @param string $uri
     */
    public function __construct(string $address, string $name, string $link, string $uri)
    {
        $this->address = $address;
        $this->name = $name;
        $this->link = $link;
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}

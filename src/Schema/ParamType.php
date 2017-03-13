<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Schema;

class ParamType
{
    /**
     * Type of data.
     *
     * @var string
     */
    private $type = 'string';

    /**
     * Extends the definition of the type.
     *
     * @var string
     */
    private $format = '';

    /**
     * Defines the format if the type is array.
     *
     * @var string
     */
    private $arrayFormat = 'csv';

    /**
     * Defines JSON items as a string.
     *
     * @var string
     */
    private $items = '';

    /**
     * @param string $type
     * @param string $format
     * @param string $arrayFormat
     * @param string $items
     */
    public function __construct($type, $format, $arrayFormat, $items)
    {
        $this->type = $type;
        $this->format = $format;
        $this->arrayFormat = $arrayFormat;
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getArrayFormat()
    {
        return $this->arrayFormat;
    }

    /**
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }
}

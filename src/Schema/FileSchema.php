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

use Katana\Sdk\Schema\Protocol\HttpFileSchema;

class FileSchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * Defines the expected MIME type of the file's content.
     *
     * MAY include multiple MIME types separated by comma.
     *
     * @var string
     */
    private $mime = 'text/plain';

    /**
     * Defines whether the parameter is required or not.
     *
     * @var bool
     */
    private $required = false;

    /**
     * @var HttpFileSchema
     */
    private $http;

    /**
     * @var FileValidation
     */
    private $validation;

    /**
     * @param string $name
     * @param string $mime
     * @param bool $required
     * @param HttpFileSchema $http
     * @param FileValidation $validation
     */
    public function __construct(
        $name,
        $mime,
        $required,
        HttpFileSchema $http,
        FileValidation $validation
    ) {
        $this->name = $name;
        $this->mime = $mime;
        $this->required = $required;
        $this->http = $http;
        $this->validation = $validation;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->validation->getMax();
    }

    /**
     * @return bool
     */
    public function isExclusiveMax()
    {
        return $this->validation->isExclusiveMax();
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->validation->getMin();
    }

    /**
     * @return bool
     */
    public function isExclusiveMin()
    {
        return $this->validation->isExclusiveMin();
    }

    /**
     * @return HttpFileSchema
     */
    public function getHttpSchema()
    {
        return $this->http;
    }
}

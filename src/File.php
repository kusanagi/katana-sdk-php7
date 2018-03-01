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

namespace Katana\Sdk;

interface File
{
    /**
     * Return the name of the File.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return the full path for the File.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Return the MIME of the File.
     *
     * @return string
     */
    public function getMime(): string;

    /**
     * Return the filename of the File without path.
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Return the size of the File in bytes.
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Return the token for the file server where the File is hosted.
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Determine if a path is defined for the File.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Determine if a File is local.
     *
     * @return bool
     */
    public function isLocal(): bool;

    /**
     * Return the contents of the file.
     *
     * @return string
     */
    public function read(): string;

    /**
     * Return a copy of the File with the given name.
     *
     * @param string $name
     * @return File
     */
    public function copyWithName($name): File;

    /**
     * Return a copy of the File with the given MIME.
     *
     * @param string $mime
     * @return File
     */
    public function copyWithMime($mime): File;
}

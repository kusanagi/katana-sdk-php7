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

class ActionRelation
{
    /**
     * @var string
     */
    private $service = '';

    /**
     * @var string
     */
    private $type = 'one';

    /**
     * @param string $service
     * @param string $type
     */
    public function __construct(
        $service,
        $type = 'one'
    ) {
        $this->service = $service;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

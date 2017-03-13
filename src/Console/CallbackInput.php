<?php
/**
 * PHP 5 SDK for the KATANA(tm) Framework (http://katana.kusanagi.io)
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

namespace Katana\Sdk\Console;

class CallbackInput
{
    /**
     * @var string
     */
    private $action = '';

    private $input;

    /**
     * @param string $action
     * @param $input
     */
    public function __construct($action, $input)
    {
        $this->action = $action;
        if ($input && file_exists($input)) {
            $this->input = file_get_contents($input);
        }
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function getInput()
    {

    }

    public function hasInput()
    {

    }
}

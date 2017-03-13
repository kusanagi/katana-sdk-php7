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

use Katana\Sdk\Exception\SchemaException;

class ActionEntity
{
    /**
     * Path to the entity in each object returned by the action.
     *
     * @var string
     */
    private $entityPath = '';

    /**
     * Delimiter used to specify the traversal between objects.
     *
     * @var string
     */
    private $pathDelimiter = '/';

    /**
     * Name of the property in the entity object which defines the primary key.
     *
     * @var string
     */
    private $primaryKey = 'id';

    /**
     * Determines if the action returns a collection of entities.
     *
     * @var bool
     */
    private $collection = false;

    /**
     * @var array
     */
    private $definition = [];

    /**
     * @param array $field
     * @return array
     */
    private function parseField(array $field)
    {
        $result = [
            'name' => $field['n'],
            'type' => $field['t'],
        ];

        if (isset($field['o'])) {
            $result['optional'] = $field['o'];
        }

        return $result;
    }

    private function parseObjectField(array $objectField)
    {
        $result = [
            'name' => $objectField['n'],
        ];

        if (isset($objectField['o'])) {
            $result['optional'] = $objectField['o'];
        }

        if (isset($objectField['f'])) {
            $result['field'] = array_map([$this, 'parseField'], $objectField['f']);
        }

        if (isset($objectField['F'])) {
            $result['fields'] = array_map([$this, 'parseObjectField'], $objectField['F']);
        }

        return $result;
    }

    /**
     * @param array $definition
     * @return array
     */
    private function parseDefinition(array $definition)
    {
        if (empty($definition)) {
            return [];
        }

        $result = [];
        if (isset($definition['f'])) {
            $result['field'] = array_map([$this, 'parseField'], $definition['f']);
        }

        if (isset($definition['F'])) {
            $result['fields'] = array_map([$this, 'parseObjectField'], $definition['F']);
        }

        if (isset($definition['V'])) {
            $result['validate'] = $definition['V'];
        }

        return $result;
    }

    /**
     * @param string $entityPath
     * @param string $pathDelimiter
     * @param string $primaryKey
     * @param bool $collection
     * @param array $definition
     */
    public function __construct(
        $entityPath,
        $pathDelimiter,
        $primaryKey,
        $collection,
        $definition = []
    ) {
        $this->entityPath = $entityPath;
        $this->pathDelimiter = $pathDelimiter;
        $this->primaryKey = $primaryKey;
        $this->collection = $collection;
        if ($definition) {
            // Definition stops being sent as an array in next alpha
            $this->definition = $this->parseDefinition($definition);
        }
    }

    /**
     * @return string
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }

    /**
     * @return string
     */
    public function getPathDelimiter()
    {
        return $this->pathDelimiter;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return boolean
     */
    public function isCollection()
    {
        return $this->collection;
    }

    /**
     * @return bool
     */
    public function hasDefinition()
    {
        return !empty($this->definition);
    }

    /**
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param array $data
     * @return array
     * @throws SchemaException
     */
    public function resolveEntity(array $data)
    {
        if (!$this->entityPath) {
            return $data;
        }

        $keys = explode($this->pathDelimiter, $this->entityPath);

        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                throw new SchemaException("Cannot resolve entity");
            }

            $data = $data[$key];
        }

        return $data;
    }
}

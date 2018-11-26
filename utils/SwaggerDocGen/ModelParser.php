<?php

namespace app\utils\SwaggerDocGen;

use yii\base\BaseObject;
use yii\base\Model;

/**
 * Parses model to definiton meta data
 * @package app\utils\SwaggerDocGen
 */
class ModelParser extends BaseObject
{
    /**
     * store all parsed definitions
     * @var array
     */
    protected $definitions = [];

    /**
     * php types that matches swagger types
     * @var array
     */
    protected $plainTypes = [
        'boolean' => 'boolean',
        'integer' => 'integer',
        'string' => 'string',
    ];

    /**
     * php types that matches swagger types after rename
     * @var array
     */
    protected $renamedTypes = [
        'double' => 'number',
    ];

    /**
     * return parsed definitions
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * adds definition
     * @param $name
     * @param $val
     */
    protected function addDefinition($name, $val)
    {
        $this->definitions[$this->getDefinitionName($name)] = $val;
    }

    /**
     * Converts class name to definition name
     * @param string $name
     * @return string
     */
    public function getDefinitionName($name)
    {
        preg_match('/[^\\\]+$/', $name, $matches);
        return $matches[0];
    }

    /**
     * Parse model attributes to definition
     * @param Model $model
     * @return array
     */
    public function parse(Model $model)
    {
        return $this->parseArray($model::className(), $model->toArray(), $model->attributeLabels());
    }

    /**
     * Parse model array to definition
     * @param string $name
     * @param array $model
     * @param array $labels
     * @return array
     */
    public function parseArray($name, array $model, array $labels = [])
    {
        while (array_key_exists(0, $model)) {
            $model = $model[0];
        }
        $data = [];
        foreach ($model as $field => $value) {
            $data = array_merge($data, $this->convertAttribute($field, $value, @$labels[$field]));
        }
        $this->addDefinition($name, $data);
        return $this->getDefinitions();
    }

    /**
     * Turns attr/value pair into data array
     * @param string $attr
     * @param mixed $value
     * @params string $label you may specify an attr label. default value matches $attr
     * @return array
     */
    public function convertAttribute($attr, $value, $label = '')
    {
        $type = gettype($value);
        $data = [
            'description' => ($label)?: $attr,
        ];
        if (array_key_exists($type, $this->plainTypes)) {
            $data['type'] = $type;
        } elseif (array_key_exists($type, $this->renamedTypes)) {
            $data['type'] = $this->renamedTypes[$type];
        } elseif ($type === 'array') {
            $this->parseArray($attr, $value);
            $data['ref'] = '#/definitions/' . $attr;
        } else {
            $data['type'] = 'string';
        }
        return [
            $attr => $data,
        ];
    }
}
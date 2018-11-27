<?php

namespace app\utils\SwaggerDocGen\RuleParsers;

use yii\base\BaseObject;

class TypeParser extends BaseObject implements IParser
{
    public $yiiType = '';

    public $swaggerType = '';

    public $params = [
        'min' => 'min',
        'max' => 'max',
    ];

    /**
     * check if rule is valid
     * @param array $rule
     * @return bool
     */
    public function validateRule(array $rule)
    {
        return @$rule[1] && $rule[1] === $this->yiiType;
    }

    /**
     * {@inheritdoc}
     */
    public function parseRule(array $rule)
    {
        if (!$this->validateRule($rule)) {
            return [];
        }
        $data = ['type' => $this->swaggerType];
        return array_merge(
            $data,
            $this->loadParams($rule, $this->params)
        );
    }

    /**
     * parse rule params
     * @param $rule
     * @param $params
     * @return array
     */
    protected function loadParams($rule, $params)
    {
        $data = [];
        foreach ($params as $param => $swaggerName) {
            if (array_key_exists($param, $rule)) {
                $data[$swaggerName] = $rule[$param];
            }
        }
        return $data;
    }
}
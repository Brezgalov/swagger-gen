<?php

namespace app\utils\SwaggerDocGen\RuleParsers;

use yii\base\BaseObject;

class DoubleParser extends TypeParser
{
    public $yiiType = '';

    public $swaggerType = '';

    protected $types = ['number', 'double'];

    /**
     * {@inheritdoc}
     */
    public function validateRule(array $rule)
    {
        return @$rule[1] && in_array($rule[1], $this->types);
    }
}
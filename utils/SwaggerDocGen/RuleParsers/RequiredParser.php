<?php

namespace app\utils\SwaggerDocGen\RuleParsers;

use yii\base\BaseObject;

class RequiredParser extends BaseObject implements IParser
{
    /**
     * {@inheritdoc}
     */
    public function parseRule(array $rule)
    {
        if (@$rule[1] && $rule[1] == 'required') {
            return ['required' => true];
        }
        return [];
    }
}
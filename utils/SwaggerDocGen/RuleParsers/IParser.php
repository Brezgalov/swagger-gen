<?php

namespace app\utils\SwaggerDocGen\RuleParsers;

interface IParser
{
    /**
     * Parse rule array to info fields
     * @param array $rule
     * @return array
     */
    public function parseRule(array $rule);
}
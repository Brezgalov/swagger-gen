<?php

namespace app\modules\SwaggerDocGen\utils;

class RulesParser extends \yii\app\BaseObject
{
    /**
     * parse result
     * @var array
     */
    protected $metaData = [];

    /**
     * list of parsers callback
     * @var array
     */
    protected $parsers = [];

    /**
     * itiniallize
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * add parser
     */
    public function addParser($parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * parse rules to get meta
     * @return array
     */
    public function parse(array $rules)
    {
        foreach($rules as $rule) {
            $data = $this->parseSingleRule($rule);
            foreach ($data['fields'] as $field) {
                $this->metaData[$field] = array_merge(
                    array_key_exists($field, $this->metaData)? $this->metaData[$field] : [],
                    $data['info']
                );
            }
        }
        return $this->metaData;
    }

    /**
     * convert single rule to some data patch
     * @return array
     */
    public function parseSingleRule(array $rule)
    {
        $data = [
            'fields' => [],
            'info' => [],
        ];
        if (!@$rule[0] || !@$rule[1]) {
            return $data;
        }
        $data['fields'] = is_array($rule[0])? $rule[0] : array((string)$rule[0]);
        foreach($this->parsers as $callback) {
            call_user_func($callback, $rule, $data['info']);
        }
        return $data;
    }
}
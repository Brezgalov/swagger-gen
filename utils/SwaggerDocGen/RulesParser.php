<?php

namespace app\utils\SwaggerDocGen;

use app\utils\SwaggerDocGen\RuleParsers\IParser;

class RulesParser extends \yii\base\BaseObject
{
    /**
     * parse result
     * @var array
     */
    protected $metaData = [];

    /**
     * list of RuleParsers callback
     * @var array
     */
    protected $parsers = [];

    /**
     * RulesParser constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Add array of IParser to rule RuleParsers
     * @param array $parsers
     */
    public function addParsers(array $parsers)
    {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    /**
     * add parser
     * @param IParser $parser
     */
    public function addParser(IParser $parser)
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
        foreach($this->parsers as $parser) {
            $data['info'] = $parser->parseRule($rule);
        }
        return $data;
    }
}
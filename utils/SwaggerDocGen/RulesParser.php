<?php

namespace app\utils\SwaggerDocGen;

use app\utils\SwaggerDocGen\RuleParsers\IntegerParser;
use app\utils\SwaggerDocGen\RuleParsers\StringParser;
use app\utils\SwaggerDocGen\RuleParsers\DoubleParser;
use app\utils\SwaggerDocGen\RuleParsers\IParser;
use app\utils\SwaggerDocGen\RuleParsers\RequiredParser;
use yii\base\Model;

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
        $this->addParsers([
            new RequiredParser(),
            new StringParser(),
            new DoubleParser(),
            new IntegerParser(),
        ]);
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
     * Parse model attributes to definition
     * @param Model $model
     * @return array
     */
    public function parse(Model $model)
    {
        $rules = $model->rules();
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
            $data['info'] = array_merge($data['info'], $parser->parseRule($rule));
        }
        var_dump([1, $rule, $data]);
        return $data;
    }
}
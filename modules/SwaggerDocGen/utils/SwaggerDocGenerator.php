<?php

namespace app\modules\SwaggerDocGen\utils;

use yii\base\Model;

/**
 * Generates swager definition and crud actions for selected model
 */
class SwaggerDocGenerator extends Model
{
    const DOC_TYPE_JSON = 'json';
    const DOC_TYPE_PHP = 'php';

    /**
     * Doc type: json/php
     * @var string
     */
    public $docType;

    /**
     * Where to save doc
     * @var string
     */
    public $docFolder;

    /**
     * Model for doc generation
     * @var Model
     */
    public $model;

    /**
     * initialises class
     */
    public function __construct($config = [])
    {
        parent::__construct($config);        
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['docType', 'docFolder', 'model'], 'required'],
            ['docType', 'in', 'range' => [self::DOC_TYPE_JSON, self::DOC_TYPE_PHP]],
            ['docFolder', 'validateDocFolder', 'message' => '{attribute} is invalid'],
            ['model', 'modelValidator', 'message' => '{attribute} is invalid'],
        ];
    }
}
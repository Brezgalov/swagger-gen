<?php

namespace app\utils\SwaggerDocGen;

use yii\base\BaseObject;
use yii\base\Model;

abstract class BaseParser extends BaseObject
{
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
     * parse model
     * @param Model $model
     * @return mixed
     */
    public abstract function parse(Model $model);
}
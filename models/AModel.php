<?php

namespace app\models;

use yii\base\Model;

class AModel extends Model
{
    public $a1;
    public $a2;
    public $a3;

    public function rules()
    {
        return [
            [['a1', 'a2'], 'required'],
            [['a1', 'a2'], 'string'],
            ['a3', 'integer', 'min' => 1, 'max' => 100],
        ];
    }
}
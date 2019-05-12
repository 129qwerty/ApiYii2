<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EntryForm extends Model
{
    public $from;
    public $to;
    public $amount;

    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['amount'], 'integer', 'min' => 1],
        ];
    }
}
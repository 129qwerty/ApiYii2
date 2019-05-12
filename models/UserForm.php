<?php

namespace app\models;

use yii\base\Model;

class UserForm extends Model
{
	public $name;

	public function rules()
	{
		return [
			[['name'], 'required']
			];
	}
}
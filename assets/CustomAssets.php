<?php 
namespace frontend\components;
use yii;

use yii\web\AssetBundle;
class CustomAssets extends AssetBundle
{
    public $css = [
        
    ];
    public $js = [
        "./web/custom.js"
    ];
    public $depends = [
    ];
}
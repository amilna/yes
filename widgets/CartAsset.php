<?php
namespace amilna\yes\widgets;

use Yii;
use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
    //public $sourcePath = '@amilna/versioning/assets';
	
	public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',        
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        //$this->js[] = YII_DEBUG ? 'js/notification.js' : 'js/notification.min.js';       
    }    
}

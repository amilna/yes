<?php

namespace amilna\yes;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'amilna\yes\controllers';
    public $userClass = 'common\models\User';//'dektrium\user\models\User';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}

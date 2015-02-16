<?php

namespace amilna\yes;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'amilna\yes\controllers';
    public $userClass = 'common\models\User';//'dektrium\user\models\User';
    public $currency = ["symbol"=>"Rp","decimal_separator"=>",","thousand_separator"=>"."];
    public $defaults = ["weight"=>0.3,"vat"=>false]; // default weight (in Kg), vat (ratio or set false to disabling vat) for non configurated data of product

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}

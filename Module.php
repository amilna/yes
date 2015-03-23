<?php

namespace amilna\yes;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'amilna\yes\controllers';
    public $userClass = 'common\models\User';//'dektrium\user\models\User';
    public $uploadDir = '@webroot/upload';
	public $uploadURL = '@web/upload';
    public $currency = ["symbol"=>"Rp","decimal_separator"=>",","thousand_separator"=>"."];
    public $defaults = ["weight"=>0.1,"vat"=>0.1]; // default weight (in Kg), vat (ratio or set false to disabling vat) for non configurated data of product
	public $company = ["name"=>"Your Company Name","address"=>"Your company address","phone"=>"+62-21-123456","email"=>"iyo@amilna.com"];
	
    public function init()
    {
        parent::init();

        // custom initialization code goes here        
    }
}

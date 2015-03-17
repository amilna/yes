<?php
namespace amilna\yes\widgets;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
use yii\helpers\Json;


class Cart extends Widget
{    	
	public $viewPath = '@amilna/yes/widgets/views/cart';
	public $icon = 'fa fa-shopping-cart';
	
	private $bundle;

    public function init()
    {
        parent::init();
        $view = $this->getView();				
		$module = Yii::$app->getModule("yes");
		$user_id = Yii::$app->user->id;
				
		$bundle = CartAsset::register($view);
		$this->bundle = $bundle;
				
						
		$script = "		
		" . PHP_EOL;
	
		$view->registerJs($script);		
		
		echo $this->render($this->viewPath,["icon"=>$this->icon]);
		echo $this->render('@amilna/yes/views/product/_script_add');        
    }
        
}

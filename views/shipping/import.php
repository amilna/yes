<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use kartik\money\MaskMoney;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\datetime\DateTimePicker;
use kartik\touchspin\TouchSpin;

use iutbay\yii2kcfinder\KCFinderInputWidget;

$module = Yii::$app->getModule('yes');
// kcfinder options
// http://kcfinder.sunhater.com/install#dynamic
$kcfOptions = array_merge([], [
    'uploadURL' => Yii::getAlias($module->uploadURL),
    'uploadDir' => Yii::getAlias($module->uploadDir),
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true,
        ],
        'dirs' => [
            'create' => true,
            'delete' => true,
            'rename' => true,
        ],
    ],
    'types'=>[
		'files'    =>  "*csv",        
        'images'   =>  "*img",
    ],
    'thumbWidth' => 200,
    'thumbHeight' => 200,        
]);

// Set kcfinder session options
Yii::$app->session->set('KCFINDER', $kcfOptions);


/* @var $this yii\web\View */
/* @var $model amilna\blog\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?= Html::beginForm(\yii\helpers\Url::toRoute(["//yes/shipping/import"]), 'post') ?>
	
	<div class="row">				
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>										
			<?php 
				echo KCFinderInputWidget::widget([
					'name'=>'Shipping[csv]',
					'value'=>$file,
					'multiple' => false,
					'kcfOptions'=>$kcfOptions,	
					'kcfBrowseOptions'=>[
						'type'=>'files',
						'lng'=>substr(Yii::$app->language,0,2),				
					]	
				]);	
			?>									
		</div>	
    </div>		    

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?= Html::endForm() ?>

</div>

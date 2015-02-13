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

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">		
			<div class="col-xs-6">
		<?= $form->field($model, 'terminal')->textInput(['maxlength' => 65,'placeholder'=>Yii::t('app','Bank or another payment gateway service')]) ?>
			</div>
			<div class="col-xs-6">		
		<?= $form->field($model, 'status')->widget(Select2::classname(), [					
					'data' => $model->itemAlias('status'),					
					'options' => [
						'placeholder' => Yii::t('app','Select status ...'), 
						'multiple' => false
					],
				]);
		?>
		
			</div>							
	</div>
	
	<div class="row">		
			<div class="col-xs-6">
		<?= $form->field($model, 'account')->textInput(['maxlength' => 65,'placeholder'=>Yii::t('app','Number account or username to access payment terminal')]) ?>
			</div>
			<div class="col-xs-6">
		<?= $form->field($model, 'name')->textInput(['maxlength' => 65,'placeholder'=>Yii::t('app','Name registered to access payment terminal')]) ?>
			</div>							
	</div>	    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

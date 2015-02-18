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
use amilna\yes\models\Payment;
use amilna\yes\models\Order;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Confirmation */
/* @var $form yii\widgets\ActiveForm */

$module = Yii::$app->getModule("yes");

$listPayment = []+ArrayHelper::map(Payment::find()->where("status = 1")->all(), 'id', 'terminal');
$payment = ($model->isNewRecord?$model->id['payment']:false);
$listOrder = []+ArrayHelper::map(Order::find()->where("status = 0 AND isdel = 0")->all(), 'id', 'reference');
?>

<div class="confirmation-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class='row'>
		<div class='col-md-4 col-sm-6 '>						
			<div class="well">
				<h4><?= Yii::t("app","Payment for")?></h4>
				<?php
								
					$field = $form->field($model,"order_id");				
					echo $field->widget(Select2::classname(),[								
						'data' => $listOrder,								
						'options' => [
							'placeholder' => Yii::t('app','Select order reference ...'), 
							'multiple' => false
						],
					]);
				?>
				<?php			
					$field = $form->field($model,"payment_id");				
					echo $field->widget(Select2::classname(),[								
						'data' => $listPayment,								
						'options' => [
							'placeholder' => Yii::t('app','Select payment method ...'), 
							'multiple' => false
						],
					]);
				?>
				<?php 
					echo $form->field($model, 'amount')->widget(MaskMoney::classname(), [								
						'pluginOptions' => [
							'prefix' => $module->currency["symbol"],
							'suffix' => '',
							'thousands' => $module->currency["thousand_separator"],
							'decimal' => $module->currency["decimal_separator"],
							'precision' => 2, 
							'allowNegative' => false
						],
						'options'=>['style'=>'text-align:right']
					]); 
				?>
			</div>
		</div>
		<div class='col-md-8 col-sm-6'>						
			<h4><?= Yii::t("app","Sender Info")?></h4>
			<div class='row'>
				<div class='col-md-6 col-sm-12'>						
					<?= $form->field($model, 'terminal')->textInput(['maxlength' => 255,'placeholder'=>Yii::t('app','Bank or another payment gateway service')]) ?>
					<?= $form->field($model, 'account')->textInput(['maxlength' => 255,'placeholder'=>Yii::t('app','Number account or username to access payment terminal')]) ?>
					<?= $form->field($model, 'name')->textInput(['maxlength' => 255,'placeholder'=>Yii::t('app','Name registered to access payment terminal')]) ?>			
				</div>
				<div class='col-md-6 col-sm-12'>											
					<?= $form->field($model, 'remarks')->textarea(['rows' => 3,'placeholder'=>Yii::t('app','Note that attached while submited payment')]) ?>
				</div>
			</div>
		</div>
	</div>
	

	<div class='row'>
		<div class='col-sm-12'>
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
			</div>
		</div>
    </div>
	<hr>
    <?php ActiveForm::end(); ?>

</div>

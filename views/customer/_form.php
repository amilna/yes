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
/* @var $model amilna\yes\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class='row'>
		<div class='col-sm-8'>
			<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'phones')->widget(Select2::classname(), [
				'options' => [
					'placeholder' => Yii::t('app','Add phones ...'),
				],
				'pluginOptions' => [
					'tags' => $model->getPhones(),
				],
			]) ?>		
			<?= $form->field($model, 'addresses')->textarea(['rows' => 3]) ?>
		</div>
		<div class='col-sm-4 well'>
			<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'last_action')->widget(Select2::classname(), [			
					'data' => $model->itemAlias('last_action'),				
					'options' => ['placeholder' => Yii::t('app','Select last action...')],
					'pluginOptions' => [
						'allowClear' => false
					],
					'pluginEvents' => [						
					],
				]);
			?>
			<?= $form->field($model, 'last_time')->widget(DateTimePicker::classname(), [				
					'options' => ['placeholder' => 'Select posting time ...'],
					'convertFormat' => true,
					'pluginOptions' => [
						'format' => 'yyyy-MM-dd HH:i:s',
						//'startDate' => '01-Mar-2014 12:00 AM',
						'todayHighlight' => true
					]
				]) 
			?>					
		</div>	
	</div>

	<div class='row'>
		<div class='col-sm-12'>
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success pull-left' : 'btn btn-primary pull-left']) ?>
			</div>
		</div>
    </div>
	<br>
    <?php ActiveForm::end(); ?>

</div>

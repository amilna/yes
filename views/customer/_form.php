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
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'phones')->textarea(['rows' => 6]) ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'addresses')->textarea(['rows' => 6]) ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'last_time')->textInput() ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'last_action')->textInput() ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<?= $form->field($model, 'isdel')->textInput() ?>
		</div>
	</div>

	<div class='row'>
		<div class='col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2'>
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
			</div>
		</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

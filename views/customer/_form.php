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
				'data'=>$model->getPhones(),
				'pluginOptions' => [
					'tags' => true,
					'tokenSeparators'=>[',',' '],
				],
			]) ?>		
			<?php
				/*= $form->field($model, 'addresses')->textarea(['rows' => 3]) */
			?>
			<div class="row">		
				<div class="col-sm-12">
					<div class="well addresses">		
						<h4><?= Yii::t('app','Addresses') ?> <small class="pull-right"><?= Yii::t('app','list of addresses') ?>  <a id="address-add" class="btn btn-sm btn-default"><?= Yii::t("app","Add Address") ?></a></small></h4>				
						<br>
					</div>
				</div>				
			</div>
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
					'options' => ['placeholder' => 'Select last action time ...','readonly'=>true],
					'removeButton'=>false,
					'convertFormat' => true,
					'pluginOptions' => [
						'format' => 'yyyy-MM-dd HH:i:s',
						//'startDate' => '01-Mar-2014 12:00 AM',
						'todayHighlight' => true
					]
				]) 
			?>
			
			<?= $form->field($model, 'remarks')->textArea(['rows' => 3]) ?>					
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

<div id="template_form_details" class="hidden">
	<div id="detail_:N" class="detail">	
		<div class="row">									
			<div class="col-xs-12" >																										
				<div class="input-group">				  
				  <textarea name="Customer[addresses][]" id="Customer_addresses_:N" rows=2 class="form-control" placeholder="<?= Yii::t("app","Address available to use for shipping")?>"></textarea>
				  <div id="address-del:N" title="<?= Yii::t('app','Remove Address')?>" class="input-group-addon" style="cursor:pointer;"><i class="glyphicon glyphicon-trash"></i></div>
				</div>
			</div>			
		</div>				
	</div>	
</div>

<?php
$this->render('@amilna/yes/views/customer/_script',['model'=>$model]);

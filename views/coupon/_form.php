<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use amilna\yap\Money;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-9">
			<div class="row">				
				<div class="col-sm-12">
			<?= $form->field($model, 'code')->textInput(['maxlength' => 65,'placeholder'=>Yii::t('app','Coupon code')]) ?>
				</div>				
			</div>
			<?= $form->field($model, 'description')->textArea(['maxlength' => 155,'placeholder'=>Yii::t('app','Description of the coupon')]) ?>					
			<div class="well">
				<h5><?= Yii::t('app','Coupon Value') ?></h5>
				<div class="row">
					<div class="col-sm-6">
					<?php		
						$module = Yii::$app->getModule('yes');
						echo $form->field($model, 'price')->widget(Money::classname(), [			
							"pluginOptions"=>	[
								 "radixPoint"=>$module->currency["decimal_separator"], 
								 "groupSeparator"=> $module->currency["thousand_separator"], 
								 "digits"=> 2,
								 "autoGroup"=> true,
								 "prefix"=> $module->currency["symbol"]
							 ],
							 "pluginEvents"=>[
								"change"=>"function(){
										
									}",
							 ],
							"options"=>['placeholder' => Yii::t('app','0,00')]
						]);	
												
					?>						
					</div>
					<div class="col-sm-2">
					<h4><?= Yii::t('app','or') ?>
					<small><?= Yii::t('app','(choose one, if both filled, price will used as default)') ?></small></h4>
					</div>
					<div class="col-sm-4">
					<?= $form->field($model, 'discount')->textInput(['type'=>'number','placeholder'=>Yii::t('app','Discount in Percent')]) ?>
					</div>
				</div>
			</div>	
		</div>
		<div class="col-md-3">
			<div class="well">
				<?= $form->field($model, 'time_from')->widget(DateTimePicker::classname(), [				
						'options' => ['placeholder' => 'Select start time ...','readonly'=>true],
						'removeButton'=>false,
						'convertFormat' => true,
						'pluginOptions' => [
							'format' => 'yyyy-MM-dd HH:i:s',
							//'startDate' => '01-Mar-2014 12:00 AM',
							'todayHighlight' => true
						]
					]) 
				?>
		
				<?= $form->field($model, 'time_to')->widget(DateTimePicker::classname(), [				
						'options' => ['placeholder' => 'Select end time ...','readonly'=>true],
						'removeButton'=>false,
						'convertFormat' => true,
						'pluginOptions' => [
							'format' => 'yyyy-MM-dd HH:i:s',
							//'startDate' => '01-Mar-2014 12:00 AM',
							'todayHighlight' => true
						]
					]) 
				?>		
				
				<br>
				
				<?= $form->field($model, 'qty')->textInput(['type'=>'number','placeholder'=>Yii::t('app','Quantity')]) ?>
				
				<?= $form->field($model, 'status')->widget(Select2::classname(), [			
						'data' => $model->itemAlias('status'),				
						'options' => ['placeholder' => Yii::t('app','Select coupon status...')],
						'pluginOptions' => [
							'allowClear' => false
						],
						'pluginEvents' => [						
						],
					]);
				?>
				
			</div>				
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

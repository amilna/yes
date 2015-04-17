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
use amilna\yes\models\PaymentSearch;
use amilna\yes\models\OrderSearch;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Confirmation */
/* @var $form yii\widgets\ActiveForm */

$module = Yii::$app->getModule("yes");

$listPayment = []+ArrayHelper::map(PaymentSearch::find()->select(["id","concat(terminal,' (',account,')') as terminal"])->andWhere("status = 1")->all(), 'id', 'terminal');
$payment = ($model->isNewRecord?$model->id['payment']:false);
$listOrder = []+ArrayHelper::map(OrderSearch::find()->andWhere("status = 0")->all(), 'id', 'reference');
?>

<div class="confirmation-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class='row'>		
		<div class='col-md-4 col-sm-6 '>						
			<div class="well">
				<h4><?= Yii::t("app","Payment for")?></h4>
				<?php
					/*			
					$field = $form->field($model,"order_id");				
					echo $field->widget(Select2::classname(),[								
						'data' => $listOrder,								
						'options' => [
							'placeholder' => Yii::t('app','Select order reference ...'), 
							'multiple' => false
						],
					]);
					*/
					$render = $model->order_id != null && $model->isNewRecord?'true':'false';
					$url = Yii::$app->urlManager->createUrl(["//yes/order/index","format"=>"json","arraymap"=>"text:reference,id:Obj"]);
					$initScript = <<< SCRIPT
					function (element, callback) {
						var id=\$(element).val();							
						if (id !== "") {
							\$.ajax("{$url}&term="+id+"&OrderSearch[id]=" + id, {
								dataType: "json"
							}).done(function(data) { 
								var data0 = JSON.parse(data[0]["id"]);
								var data1 = {"id":data0["id"],"text":data[0]["text"]};
								callback(data1);								
								if ({$render})
								{
									//console.log(data0);
									var data2 = JSON.parse(data0["data"]);
									\$("#confirmation-payment_id").val(data2["payment"]);
									\$("#confirmation-amount").val(data0["total"]);
									\$("#confirmation-amount-disp").val(data0["total"]);									
									var \$el = \$("#confirmation-payment_id"),settings = \$el.attr('data-krajee-select2');
									settings = window[settings];								
									\$el.select2(settings);
								}
							});
						}
					}
SCRIPT;
					echo $form->field($model, 'order_id')->widget(Select2::classname(), [
						'options' => ['placeholder' => 'Select order reference ...'],
						'pluginOptions' => [
							'allowClear' => true,
							'minimumInputLength' => 3,
							'ajax' => [
								'url' => $url,
								'dataType' => 'json',
								'data' => new JsExpression('function(term,page) { return {"OrderSearch[reference]":term}; }'),
								'results' => new JsExpression('function(data,page) { return {results:data}; }'),								
							],
							'initSelection' => new JsExpression($initScript)							
						],
						'pluginEvents' => [
							'change'=>'function(){
											var obj = $("#confirmation-order_id").val();											
											if (obj != null)
											{
												var data0 = JSON.parse(obj);													
												var data2 = JSON.parse(data0["data"]);
												$("#confirmation-payment_id").val(data2["payment"]);
												$("#confirmation-amount").val(data0["total"]);
												$("#confirmation-amount-disp").val(data0["total"]);
												$("#confirmation-order_id").val(data0["id"]);
											}
											else
											{
												$("#confirmation-payment_id").val(false);
												$("#confirmation-amount").val(0);
												$("#confirmation-amount-disp").val(0);
											}																							
											var $el = $("#confirmation-payment_id"),settings = $el.attr("data-krajee-select2");
											settings = window[settings];								
											$el.select2(settings);
										}'
						]	
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
					echo $form->field($model, 'amount')->widget(Money::classname(), [			
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
	<br>
    <?php ActiveForm::end(); ?>

</div>

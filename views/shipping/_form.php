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
/* @var $model amilna\yes\models\Shipping */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shipping-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class='row'>
		<div class='col-sm-6'>
			<div class='row'>
				<div class='col-xs-8'>
					<?php 
						$field = $form->field($model,"code");
						//$field->template = "{input}";
						echo $field->widget(AutoComplete::classname(),[															
							'clientOptions' => [
								'source' => Yii::$app->urlManager->createUrl("//yes/shipping/index?format=json&arraymap=code"),
							],
							'clientEvents' => [				
								'select' => 'function(event, ui) {												
												console.log(event,ui,"tes");							
											}',
							],
							'options'=>[
								'class'=>'form-control required','maxlength' => 255,				
								'placeholder' => Yii::t('app','Unique shipping code')
							]
						]); 
					?>
					
				</div>
				<div class='col-xs-4'>
					<?= $form->field($model, 'status')->widget(Select2::classname(), [			
							'data' => $model->itemAlias('status'),				
							'options' => ['placeholder' => Yii::t('app','Select shipping status...')],
							'pluginOptions' => [
								'allowClear' => false
							],
							'pluginEvents' => [						
							],
						]);
					?>
				</div>		
			</div>
			<?php 
				$field = $form->field($model,"city");
				//$field->template = "{input}";
				echo $field->widget(AutoComplete::classname(),[															
					'clientOptions' => [
						'source' => Yii::$app->urlManager->createUrl("//yes/shipping/index?format=json&arraymap=city"),
					],
					'clientEvents' => [				
						'select' => 'function(event, ui) {												
										console.log(event,ui,"tes");							
									}',
					],
					'options'=>[
						'class'=>'form-control required','maxlength' => 255,				
						'placeholder' => Yii::t('app','City name that use by shipping provider')
					]
				]); 
			?>	
			<?php 
				$field = $form->field($model,"area");
				//$field->template = "{input}";
				echo $field->widget(AutoComplete::classname(),[															
					'clientOptions' => [
						'source' => Yii::$app->urlManager->createUrl("//yes/shipping/index?format=json&arraymap=area"),
					],
					'clientEvents' => [				
						'select' => 'function(event, ui) {												
										console.log(event,ui,"tes");							
									}',
					],
					'options'=>[
						'class'=>'form-control required','maxlength' => 255,				
						'placeholder' => Yii::t('app','Wider area of the city like province and state, ex: Jakarta, Indonesia')
					]
				]) 
			?>			
		</div>
		<div class="col-sm-6">
			<div class="well data">		
				<h4><?= Yii::t('app','Shipping Data') ?> <small class="pull-right"><?= Yii::t('app','list of carrier/provider that available to reach the city') ?>  <a id="data-add" class="btn btn-sm btn-default">Add Data</a></small></h4>				
				<br>
			</div>
		</div>	
	</div>

	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
	</div>


    <?php ActiveForm::end(); ?>

</div>

<div id="template_form_details" class="hidden">
	<div id="detail_:N" class="detail">	
		<div class="row">			
			<div class="col-xs-3" style="padding-right:0px;">																						
				<?= Html::textInput("Shipping[data][:N][provider]",false,["id"=>"Shipping_data_:N_provider","class"=>"form-control","placeholder"=>Yii::t("app","Carrier/Provider"),"style"=>"width:100%"]) ?>
			</div>	
			<div class="col-xs-3" style="padding-left:0px;">																						
				<?= Html::textInput("Shipping[data][:N][cost]",false,["id"=>"Shippingt_data_:N_cost","class"=>"form-control","placeholder"=>Yii::t("app","Cost per Kg"),"style"=>"width:100%"]) ?>
			</div>
			<div class="col-xs-6" style="padding-left:0px;">																										
				<div class="input-group">				  
				  <input name="Shipping[data][:N][remarks]" id="Shipping_data_:N_remarks" type="text" class="form-control" placeholder="<?= Yii::t("app","Detail info, ex: time estimation, etc")?>">
				  <div id="data-del:N" title="<?= Yii::t('app','Remove Data')?>" class="input-group-addon" style="cursor:pointer;"><i class="glyphicon glyphicon-trash"></i></div>
				</div>
			</div>			
		</div>				
	</div>	
</div>


<?php

$this->render('@amilna/yes/views/shipping/_script',['model'=>$model]);

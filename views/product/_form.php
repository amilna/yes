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
use amilna\yes\models\Category;
use iutbay\yii2kcfinder\KCFinderInputWidget;

$module = Yii::$app->getModule('yes');
// kcfinder options
// http://kcfinder.sunhater.com/install#dynamic
$kcfOptions = array_merge([], [
    'uploadURL' => Yii::getAlias('@web').'/'.$module->uploadDir,    
    'uploadDir' => Yii::getAlias('@webroot').'/'.$module->uploadDir,    
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => false,
            'copy' => false,
            'move' => false,
            'rename' => false,
        ],
        'dirs' => [
            'create' => true,
            'delete' => false,
            'rename' => false,
        ],
    ],  
    'types'=>[
		'files'    =>  "",        
        'images'   =>  "*img",
    ]      
]);

// Set kcfinder session options
Yii::$app->session->set('KCFINDER', $kcfOptions);

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Product */
/* @var $form yii\widgets\ActiveForm */

$cat = new Category();
$listCategory = []+ArrayHelper::map($cat->parents(), 'id', 'title');
$category = ($model->isNewRecord?$model->id['category']:[]);
foreach ($model->catPro as $c)
{
	array_push($category,$c->category_id);	
}
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-9">
			<div class="row">				
				<div class="col-xs-10">
			<?= $form->field($model, 'title')->textInput(['maxlength' => 65,'placeholder'=>Yii::t('app','Title contain a seo keyword if possible')]) ?>
				</div>
				<div class="col-xs-2">
			<?= $form->field($model, 'isfeatured')->widget(SwitchInput::classname(), [			
					'type' => SwitchInput::CHECKBOX,				
				]);
			?>		
				</div>						
			</div>
			<?= $form->field($model, 'description')->textArea(['maxlength' => 155,'placeholder'=>Yii::t('app','This description also used as meta description')]) ?>
			<?/*= $form->field($model, 'data')->textArea(['placeholder'=>Yii::t('app','Product Data')]) */?>
			
			<div class="row">		
				<div class="col-sm-12">
					<div class="well data">		
						<h4><?= Yii::t('app','Product Data') ?> <small class="pull-right"><?= Yii::t('app','list of details/data that needed for transaction') ?>  <a id="data-add" class="btn btn-sm btn-default">Add Data</a></small></h4>				
						<br>
					</div>
				</div>				
			</div>

			<?php 
			use vova07\imperavi\Widget;
			echo $form->field($model, 'content')->widget(Widget::className(), [
				'settings' => [
					'lang' => substr(Yii::$app->language,0,2),
					'minHeight' => 400,
					'toolbarFixedTopOffset'=>50,			
					'imageUpload' => Url::to(['//yes/default/image-upload']),
					'imageManagerJson' => Url::to(['//yes/default/images-get']),			
					'fileUpload' => Url::to(['//yes/default/file-upload']),
					'fileManagerJson' => Url::to(['//yes/default/files-get']),
					'plugins' => [				
						'imagemanager',
						'filemanager',
						'video',
						'table',
						'clips',				
						'fullscreen'
					]
				],
				'options'=>["style"=>"width:100%"]
			]);
			?>
		</div>
		<div class="col-md-3">
			<div class="well">
				<?= $form->field($model, 'time')->widget(DateTimePicker::classname(), [				
						'options' => ['placeholder' => 'Select posting time ...','readonly'=>true],
						'removeButton'=>false,
						'convertFormat' => true,
						'pluginOptions' => [
							'format' => 'yyyy-MM-dd HH:i:s',
							//'startDate' => '01-Mar-2014 12:00 AM',
							'todayHighlight' => true
						]
					]) 
				?>
		
				<?= $form->field($model, 'tags')->widget(Select2::classname(), [
					'options' => [
						'placeholder' => Yii::t('app','Put additional tags ...'),
					],
					'pluginOptions' => [
						'tags' => $model->getTags(),
					],
				]) ?>		
				
				<div class="form-group">
					<label for="Product[category]"><?= Yii::t('app','Category') ?></label>
					<?= Select2::widget([
						'name' => 'Product[category]', 
						'data' => $listCategory,
						'value'=>$category,
						'options' => [
							'placeholder' => Yii::t('app','Select categories ...'), 
							'multiple' => true
						],
					]);
					?>
				</div>	
				
				<br>
				<?php		
					$module = Yii::$app->getModule('yes');
					echo $form->field($model, 'price')->widget(MaskMoney::classname(), [								
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
				
				<?= $form->field($model, 'discount')->textInput(['type'=>'number','placeholder'=>Yii::t('app','Discount if sale')]) ?>
				
				<?= $form->field($model, 'status')->widget(Select2::classname(), [			
						'data' => $model->itemAlias('status'),				
						'options' => ['placeholder' => Yii::t('app','Select product status...')],
						'pluginOptions' => [
							'allowClear' => false
						],
						'pluginEvents' => [						
						],
					]);
				?>
				
				<?php 

				echo $form->field($model, 'images')->widget(KCFinderInputWidget::className(), [
					'multiple' => true,
					'kcfOptions'=>$kcfOptions,
					'kcfBrowseOptions'=>[
						'type'=>'images'				
					]	
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


<div id="template_form_details" class="hidden">
	<div id="detail_:N" class="detail">	
		<div class="row">			
			<div class="col-xs-3" style="padding-right:0px;">																						
				<div class="kv-plugin-loading loading-w0:N">&nbsp;</div>				
				<?= Html::dropDownList("Product[data][:N][type]",false,["list","text","number","long message","label","hidden"],["id"=>"w0:N","class"=>"form-control kv-hide input-md data-:T-type","placeholder"=>Yii::t("app","Select data type..."),"style"=>"width:100%","data-krajee-select2"=>"select2_x"]) ?>
			</div>	
			<div class="col-xs-3" style="padding-left:0px;">																						
				<?= Html::textInput("Product[data][:N][label]",false,["id"=>"Product_data_:N_label","class"=>"form-control","placeholder"=>Yii::t("app","Label or name"),"style"=>"width:100%"]) ?>
			</div>
			<div class="col-xs-6" style="padding-left:0px;">																										
				<div class="input-group">				  
				  <input name="Product[data][:N][value]" id="Product_data_:N_value" type="text" class="form-control" placeholder="<?= Yii::t("app","Default value or list of items (separete with commas)")?>">
				  <div id="data-del:N" title="<?= Yii::t('app','Remove Data')?>" class="input-group-addon" style="cursor:pointer;"><i class="glyphicon glyphicon-trash"></i></div>
				</div>
			</div>			
		</div>				
	</div>	
</div>


<?php

$this->render('@amilna/yes/views/product/_script',['model'=>$model]);


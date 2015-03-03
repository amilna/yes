<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use amilna\yap\GridView;

/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Customer',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false		
		'caption'=>Yii::t('app', 'Customer'),
		'headerRowOptions'=>['class'=>'kartik-sheet-style','style'=>'background-color: #fdfdfd'],
		'filterRowOptions'=>['class'=>'kartik-sheet-style skip-export','style'=>'background-color: #fdfdfd'],
		'pjax' => false,
		'bordered' => true,
		'striped' => true,
		'condensed' => true,
		'responsive' => true,
		'hover' => true,
		'showPageSummary' => true,
		'pageSummaryRowOptions'=>['class'=>'kv-page-summary','style'=>'background-color: #fdfdfd'],
		'tableOptions'=>["style"=>"margin-bottom:50px;"],
		'panel' => [
			'type' => GridView::TYPE_DEFAULT,
			'heading' => false,
		],
		'toolbar' => [
			['content'=>				
				Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
			],
			'{export}',
			'{toggleData}'
		],
		'beforeHeader'=>[
			[
				/* uncomment to use additional header
				'columns'=>[
					['content'=>'Group 1', 'options'=>['colspan'=>6, 'class'=>'text-center','style'=>'background-color: #fdfdfd']], 
					['content'=>'Group 2', 'options'=>['colspan'=>6, 'class'=>'text-center','style'=>'background-color: #fdfdfd']], 					
				],
				*/
				'options'=>['class'=>'skip-export'] // remove this row from export
			]
		],
		'floatHeader' => true,		
		
		/* uncomment to use megeer some columns
        'mergeColumns' => ['Column 1','Column 2','Column 3'],
        'type'=>'firstrow', // or use 'simple'
        */
        
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            'name',
            'email:email',
            'phones:ntext',
            'addresses:ntext',            
            [				
				'attribute' => 'last_time',
				'value' => 'last_time',				
				'filterType'=>GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'format' => 'YYYY-MM-DD HH:mm:ss',				
						'todayHighlight' => true,
						'timePicker'=>true,
						'timePickerIncrement'=>15,
						'opens'=>'left'
					],
					'pluginEvents' => [
					"apply.daterangepicker" => 'function() {									
									$(this).change();
								}',
					],			
				],
			],
			[	
				'class' => 'kartik\grid\EditableColumn',
				'attribute'=>'last_action',
				'filterType'=>GridView::FILTER_SELECT2,				
				'filterWidgetOptions'=>[
					'data'=>$searchModel->itemAlias('last_action'),
					'options' => ['placeholder' => Yii::t('app','Last action...')],
					'pluginOptions' => [
						'allowClear' => true
					],
					
				],
				'value'=>function($data){										
					return $data->itemAlias('last_action',$data->last_action);
				},
				'editableOptions'=> function ($model, $key, $index) {
					return [
						'header'=>Yii::t('app','Last action'), 
						'size'=>'sm',
						'inputType' => \kartik\editable\Editable::INPUT_SELECT2,
						'options' => [
							'data'=>$model->itemAlias('last_action'),
							'options' => ['placeholder' => Yii::t('app','Last action...')],
							'pluginOptions' => [
								'allowClear' => false
							],							
						],
						'placement'=>'left',	
						'showButtons'=>false,	
						'resetButton'=>false,						
						'pluginEvents'=>[
							'editableSuccess'=>"function(event, val, form, data) { 
													var model = JSON.parse(data.data);													
													for (m in model)
													{
														$('tr[data-key='+data.id+'] td').each(function(n,d){															
															if (n == m) {
																$(d).html(model[m]);
															}
														});																												
													}													
												}",
						],
					];
				},
				'hAlign'=>'right',
            
            ],  			
            //'last_action',
            // 'isdel',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>

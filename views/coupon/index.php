<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use amilna\yap\GridView;

/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupons');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('app', 'Coupon'),
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false		
		'caption'=>Yii::t('app', 'Coupon'),
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
		'tableOptions'=>["style"=>"margin-bottom:100px;"],
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
            'code',
            'description',
            [				
				'attribute' => 'price',				
				'value'=>function($data){										
					$module = Yii::$app->getModule('yes');
					return number_format($data->price,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},				
				'hAlign'=>'right',
				'pageSummary'=>function ($summary, $data, $widget) { 					
					$module = Yii::$app->getModule('yes');
					$r = 0;
					foreach($data as $d)
					{
						$r += floatval(str_replace($module->currency["thousand_separator"],"",$d));
					}
					return number_format($r,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},
				'pageSummaryFunc'=>'sum'
				
			], 
            'discount',
            [				
				'attribute' => 'time_from',
				'value' => 'time_from',				
				'filterType'=>GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'format' => 'YYYY-MM-DD HH:mm:ss',				
						'todayHighlight' => true,
						'timePicker'=>true,
						'timePickerIncrement'=>15,
						//'opens'=>'left'
					],
					'pluginEvents' => [
					"apply.daterangepicker" => 'function() {									
									$(this).change();
								}',
					],			
				],
			],                        			
            [				
				'attribute' => 'time_to',
				'value' => 'time_to',				
				'filterType'=>GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'format' => 'YYYY-MM-DD HH:mm:ss',				
						'todayHighlight' => true,
						'timePicker'=>true,
						'timePickerIncrement'=>15,
						//'opens'=>'left'
					],
					'pluginEvents' => [
					"apply.daterangepicker" => 'function() {									
									$(this).change();
								}',
					],			
				],
			],                        			            
            [				
				'attribute' => 'qty',				
				'value'=>function($data){										
					$module = Yii::$app->getModule('yes');
					return number_format($data->qty,0,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},				
				'hAlign'=>'right',
				'pageSummary'=>function ($summary, $data, $widget) { 					
					$module = Yii::$app->getModule('yes');
					$r = 0;
					foreach($data as $d)
					{
						$r += floatval(str_replace($module->currency["thousand_separator"],"",$d));
					}
					return number_format($r,0,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},
				'pageSummaryFunc'=>'sum'
				
			],            
			[
				'attribute'=>'status',
				'format'=>'html',
				'filterType'=>GridView::FILTER_SELECT2,				
				'filterWidgetOptions'=>[
					'data'=>$searchModel->itemAlias('status'),
					'options' => ['placeholder' => Yii::t('app','Filter by status...')],
					'pluginOptions' => [
						'allowClear' => true
					],
					
				],
				'value'=>function($model){									
					$labels = ["warning","success","info","primary","danger"];
					$html = "<span class='label label-".$labels[$model->status]."'>".Html::encode($model->itemAlias('status',$model->status))."</span>";										
					return $html;
				},				
            ],              
            // 'time',
            // 'isdel',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>

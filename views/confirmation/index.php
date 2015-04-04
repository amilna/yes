<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use amilna\yap\GridView;

/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\ConfirmationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Confirmations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;

$module = Yii::$app->getModule('yes');
?>
<div class="confirmation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('app', 'Confirmation'),
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false		
		'caption'=>Yii::t('app', 'Confirmation'),
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

            [				
				'attribute' => 'time',
				'value' => 'time',				
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
				'attribute'=>'orderReference',
				'format'=>'html',
				'value'=>function($data) {
					if ($data->order_id != null)
					{					
						return Html::a($data->order->reference,['//yes/order/view',"id"=>$data->order_id]);	
					}
					else
					{
						return "";	
					}
				}
			],
            [
				'attribute'=>'paymentTerminal',
				'format'=>'html',
				'value'=>function($data) {					
					return Html::encode($data->payment->terminal." ".$data->payment->account);	
				}
			],
            'terminal',
            'account',
            'name',
            [				
				'attribute' => 'amount',				
				'value'=>function($data) use ($module){															
					return number_format($data->amount,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},				
				'hAlign'=>'right',
				'pageSummary'=>function ($summary, $data, $widget) use ($module) { 										
					$r = 0;
					foreach($data as $d)
					{
						$r += floatval(str_replace($module->currency["thousand_separator"],"",$d));
					}
					return number_format($r,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
				},
				'pageSummaryFunc'=>'sum'
				
			],            
            'remarks:ntext',            
            // 'isdel',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>

</div>

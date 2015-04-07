<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use amilna\yap\GridView;
use amilna\yes\models\Payment;

/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row-fluid">
    <span class="col-xs-12 alert alert-info"><?= Yii::t('app', 'Be sure that reference column and customer column not empty. You may fill customer colummn with name or email or phone that used in invoice.') ?></span>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false		
		'caption'=>Yii::t('app', 'Order'),
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
				'attribute'=>'reference',
				'format'=>'html',
				'value'=>function($model){																				
					return Html::encode($model->reference);					
				},				
            ],                        
            [
				'attribute'=>'customerName',
				'format'=>'html',
				'value'=>function($model){																				
					$html = Html::encode($model->customer->name);
					$html .= "<h5>".Yii::t("app","Email")." <small>".Html::encode($model->customer->email)."</small></h5>";
					$html .= "<h5>".Yii::t("app","Phones")." <small>".Html::encode($model->customer->phones)."</small></h5>";
					
					return $html;
				},				
            ],                        
			[
				'attribute'=>'data',
				'format'=>'html',
				'value'=>function($model){										
					$module = Yii::$app->getModule('yes');
					$data = json_decode($model->data);
					//$customer = $data->customer;
					$shipping = isset($data->shipping)?json_decode($data->shipping):false;
					$cart = isset($data->cart) && $data->cart != "null"?json_decode($data->cart):[];
					//$payment = isset($data->payment)?Payment::findOne($data->payment):false;					
					
					$cm = "";
					foreach ($cart as $c)
					{
						$cm .= ($cm==""?"":", ").Html::encode($c->title)." (".$c->quantity.")";
					}
					
					$html = Yii::t("app","Order Details:");
					$html .= "<h5>".Yii::t("app","Cart")." <small>".$cm."</small></h5>";
					if ($shipping)
					{
						$html .= "<h5>".Yii::t("app","Shipping Cost")." <small><i>".Html::encode($shipping->provider.' '.$shipping->code).'</i> '.Yii::t("app","destination ").Html::encode($shipping->city.' ('.$shipping->area.")")."</small></h5>";
					}
					
					return $html;
				},				
            ],
            [				
				'attribute' => 'total',				
				'value'=>function($data){										
					$module = Yii::$app->getModule('yes');
					return number_format($data->total,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);
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
            'complete_reference',
            //'data:ntext',
            // 'status',
            // 'time',
            // 'complete_reference',
            // 'complete_time',
            // 'log:ntext',
            // 'isdel',

            [
				'class' => 'kartik\grid\ActionColumn',				
				'template'=> '{view}',
				'buttons'=>[
					'view'=>function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',["view","reference"=>$model->reference],["title"=>Yii::t("yii","View")]);
					},						
				]
			],
        ],
    ]); ?>

</div>

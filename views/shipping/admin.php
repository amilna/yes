<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use amilna\yap\GridView;

use iutbay\yii2kcfinder\KCFinderInputWidget;

$module = Yii::$app->getModule('yes');
// kcfinder options
// http://kcfinder.sunhater.com/install#dynamic
$kcfOptions = array_merge([], [
    'uploadURL' => Yii::getAlias($module->uploadURL),
    'uploadDir' => Yii::getAlias($module->uploadDir),
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true,
        ],
        'dirs' => [
            'create' => true,
            'delete' => true,
            'rename' => true,
        ],
    ],
    'types'=>[
		'files'    =>  "",        
        'images'   =>  "*img",
    ],
    'thumbWidth' => 200,
    'thumbHeight' => 200,        
]);

// Set kcfinder session options
Yii::$app->session->set('KCFINDER', $kcfOptions);



/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\ShippingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shippings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>    
	
	<div class="row">
		<div class="col-sm-3">
			<p>
				<?= Html::a(Yii::t('app', 'Create {modelClass}', [
					'modelClass' => Yii::t('app', 'Shipping'),
				]), ['create'], ['class' => 'btn btn-success']) ?>
			</p>
			
			<div id="csvs" class="well">
			<h4><?= Yii::t("app","Import")?></h4>	
			<?php 				
				echo Html::textInput("Shipping[csv]",false,['id'=>'shipping-csv','class'=>'form-control','placeholder'=>Yii::t('app','Url of csv or uploaded csv')]);
				echo KCFinderInputWidget::widget([
					'name'=>'csv_url',
					//'value'=>$file,
					'multiple' => false,
					'kcfOptions'=>$kcfOptions,	
					'kcfBrowseOptions'=>[
						'type'=>'files',
						'lng'=>substr(Yii::$app->language,0,2),				
					]	
				]);	
			?>	
							
			</div>																		
			
			<div class="form-group">					
				<a id='shipping-import-cancel' class='btn btn-danger'><?= Yii::t('app', 'Cancel') ?></a>
				<a id='shipping-import' class='btn btn-primary pull-right'><?= Yii::t('app', 'Import') ?></a>
			</div>
			<div id='shipping-import-bar'>
				<div class="progress progress-info progress-striped"><div class="bar" style="width: 0%"></div></div>
			</div>
			
		</div>	
		<div class="col-sm-9">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false		
		'caption'=>Yii::t('app', 'Shipping'),
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

            //'id',
            'code',
            'city',
            'area',
            //'data:ntext',
            [
				'attribute'=>'data',
				'format'=>'html',
				'value'=>function($data){																				
					$model = json_decode($data->data);
					$html = Yii::t("app","Providers");
					foreach ($model as $m)
					{
						$html .= "<h5>".Html::encode($m->provider)." <small>".$data->toMoney(empty($m->cost)?0:Html::encode($m->cost)).", ".Html::encode($m->remarks)."</small></h5>";
					}										
					return $html;
				},				
            ], 
            [				
				'attribute'=>'status',				
				'value'=>function($data){										
					return $data->itemAlias('status',$data->status);
				},
				'filterType'=>GridView::FILTER_SELECT2,				
				'filterWidgetOptions'=>[
					'data'=>$searchModel->itemAlias('status'),
					'options' => ['placeholder' => Yii::t('app','Filter by status...')],
					'pluginOptions' => [
						'allowClear' => true
					],
					
				],
            ],
            // 'isdel',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
    </div>

</div>

<script type="text/javascript">
<?php $this->beginBlock('SHIP_IMPORT') ?>		
	
	function importCsv(data)
	{
		var url = '<?= \yii\helpers\Url::toRoute(["//yes/shipping/admin"]) ?>';
		
		$.post(url, data, 
			function(json) 
			{						
				json = jQuery.parseJSON(json);	
				if (json.status == 1 && json.count >= 0)
				{					
					tot = parseInt(json.count);										
					//data["Shipping[n]"] = parseInt(json.n)+1;															
					
					if (parseInt(json.n) < tot)
					{
						importCsv(data);
					}
										
					var pw = (parseInt(json.n)/tot*100);										
					var prog = "<div class=\"progress\"><div class=\"progress-bar progress-bar-primary progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"+pw+\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: "+pw+"%\"><span class=\"sr-only\">"+pw+"% Complete (success)</span></div></div>";
					$("#shipping-import-bar").html(prog);
					
					if (tot == 0)
					{
						window.location.reload();
					}
				}				
				else if (json.status == 1 && json.count == 0)
				{
					var prog = "<div class=\"progress progress-info progress-striped\"><div class=\"bar\" style=\"width: 100%\"></div></div>";
					$("#shipping-import-bar").html(prog);					
					window.location.reload();
				}				
				else
				{										
					alert("<?= Yii::t("app","Import failed!") ?>");
				}
				
			}
		);	
	}
	
	function baseName(str,wext)
	{
		var base = new String(str).substring(str.lastIndexOf('/') + 1); 
		if(base.lastIndexOf(".") != -1 && typeof wext == "undefined") {
			base = base.substring(0, base.lastIndexOf("."));		
		}    
		return base;
	}
	
	$('#csvs .kcf-thumbs').bind("DOMSubtreeModified",function(){	
		var sel = $('#csvs .kcf-thumbs input[name=csv_url]');
		var url = "";
		if (sel.length > 0 && $('#csvs .kcf-thumbs').html().replace(/ /g,"") != "")
		{
			url = sel.val();
			$('#csvs .kcf-thumbs img').each(function(i,img){
				var src = $(img).attr("src");
				var ext = baseName(src);
				if (ext != 'xls')
				{
					$(img).attr("src",src.replace(ext+".png","xls.png"));
				}			
			});
			url = baseName(url.replace(/%20/g," "),true);
		}	
		$('#shipping-csv').val(url);
	});
	
	$("#shipping-import-cancel").click(
		function()
		{
			window.location.reload();
			var data = {};											
			data = {"Shipping[n]":-1};
			importCsv(data);														
		}
	);
	
	$("#shipping-import").click(
		function()
		{
			var data = {};											
			//data = {"Shipping[csv]":$("#shipping-csv").val(),"Shipping[n]":0};
			data = {"Shipping[csv]":$("#shipping-csv").val()};
			importCsv(data);														
		}
	);	

<?php $this->endBlock(); ?>
</script>

<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['SHIP_IMPORT'], yii\web\View::POS_END);

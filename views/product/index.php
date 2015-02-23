<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use himiklab\colorbox\Colorbox;

/* @var $this yii\web\View */
/* @var $searchModel amilna\yes\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'YES'), 'url' => ['/yes/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['cboxTarget'] = [];
?>
<div class="post-index">
    
    <div class="pull-right col-md-3 col-xs-6">
		<form action="<?=Yii::$app->urlManager->createUrl("//yes/product")?>" method="get">
			<div class="input-group">
				<input class="form-control input-md" name="ProductSearch[term]" id="appendedInputButtons" type="text">
				<span class="input-group-btn">
					<button class="btn btn-md" type="submit">Search</button>
				</span>
			</div>
		</form>
	</div>
	<h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>    
		
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['class' => 'col-md-3 col-sm-6','tag'=>'div'],		
		//'summary'=>Yii::t('app','List of account codes where increase on receipt or revenues'),		
		'itemView'=>'_itemIndex',
		'options' => ['class' => 'row text-center'],		
		'layout'=>"{items}\n{pager}",
	]) ?>	

</div>

<?= Colorbox::widget([
    'targets' => $this->params['cboxTarget'],        
    'coreStyle' => 1
]) ?>

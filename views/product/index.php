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
$dataProvider->pagination = [
	'pageSize'=> 12,
];
?>
<div class="post-index">
        
	<h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>    
		
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['class' => 'col-md-3 col-sm-6 item','tag'=>'div'],		
		//'summary'=>Yii::t('app','List of account codes where increase on receipt or revenues'),		
		'itemView'=>'_itemIndex',
		'options' => ['class' => 'row text-center list-view'],		
		'layout'=>"{items}\n{pager}",
		'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
	]) ?>	

</div>

<?= Colorbox::widget([
    'targets' => $this->params['cboxTarget'],        
    'coreStyle' => 1
]) ?>

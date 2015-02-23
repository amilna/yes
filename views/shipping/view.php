<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Shipping */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shippings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
	
	<?php
		$data = json_decode($model->data);
		$html = Yii::t("app","Providers");
		foreach ($data as $m)
		{
			$html .= "<h5>".Html::encode($m->provider)." <small>".$model->toMoney(empty($m->cost)?0:Html::encode($m->cost)).", ".Html::encode($m->remarks)."</small></h5>";			
		}											
	?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'city',
            'area',            
            [
				'attribute'=>'data',
				'format'=>'html',
				'value'=>$html,					
            ],
            [
				'attribute'=>'status',
				'value'=>$model->itemAlias('status',$model->status)
            ],            
        ],
    ]) ?>

</div>

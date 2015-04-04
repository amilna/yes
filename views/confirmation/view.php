<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Confirmation */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Confirmations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirmation-view">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'time',
            [
				'attribute'=>'order_id',
				'value'=>$model->order?$model->order->reference:""
            ],
            [
				'attribute'=>'payment_id',
				'value'=>$model->payment->terminal." ".$model->payment->account." ".$model->payment->name
            ],
            'terminal',
            'account',
            'name',
            [
				'attribute'=>'amount',
				'value'=>$model->toMoney($model->amount)
            ],
            'remarks:ntext',            
            //'isdel',
        ],
    ]) ?>

</div>

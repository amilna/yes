<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Sale */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sale',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sale-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

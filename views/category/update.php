<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\ProductCategory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Product Category',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="product-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

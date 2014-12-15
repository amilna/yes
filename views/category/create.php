<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model amilna\yes\models\ProductCategory */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Product Category',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

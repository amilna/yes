<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Shipping */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Shipping',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shippings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

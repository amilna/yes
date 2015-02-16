<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Customer */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Customer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

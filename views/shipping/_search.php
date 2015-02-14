<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\ShippingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shipping-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'area') ?>

    <?= $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

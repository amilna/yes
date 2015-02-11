<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="yes-default-index">
    
    <div class="jumbotron">
		<h2>YES (Yii2 E-Commerce Support)</h2>
        <h1>Congratulations!</h1>
        

        <p class="lead">You have successfully installed E-Commerce Support for your Yii-powered application.</p>

        <p><?= Html::a(Yii::t('app','Get start to create a product category'),['//yes/category/create'],["class"=>"btn btn-lg btn-success"])?>
        <?= Html::a(Yii::t('app','or Get start to create a payment terminals'),['//yes/payment/create'],["class"=>"btn btn-lg btn-warning"])?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Products</h2>

                <p>Anything that can be offered to a market that might satisfy a want or need. In retailing, products are called merchandise.</p>

                <p><?= Html::a(Yii::t('app','Go to Products'),['//yes/product'],["class"=>"btn btn-primary"])?>
                <?= Html::a(Yii::t('app','Manage Products'),['//yes/product/admin'],["class"=>"btn btn-danger"])?></p>
            </div>
            <div class="col-lg-4">
                <h2>Orders and Sales</h2>

                <p>Requests sent to obtain purchased goods and services, and closed by exchange of a commodity for money or service in return for money.</p>

                <p><?= Html::a(Yii::t('app','Manage Orders'),['//yes/order'],["class"=>"btn btn-primary"])?>
                <?= Html::a(Yii::t('app','Manage Sales'),['//yes/sale'],["class"=>"btn btn-danger"])?></p>
            </div>
            <div class="col-lg-4">
                <h2>Customers and Payment Confirmation</h2>

                <p>The recipient of a good, service, product, or idea, obtained from a seller, vendor, or supplier for a monetary or other valuable consideration.</p>

                <p><?= Html::a(Yii::t('app','Manage Customers'),['//yes/customer'],["class"=>"btn btn-primary"])?>
                <?= Html::a(Yii::t('app','Manage Confirmations'),['//yes/confirmation'],["class"=>"btn btn-danger"])?></p>
            </div>
        </div>

    </div>
</div>

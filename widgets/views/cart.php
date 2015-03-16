<?php

use yii\helpers\Html;
$n = count(Yii::$app->session->get('YES_SHOPCART'));

?>

<!-- Notifications: style can be found in dropdown.less -->
<li class="dropdown notifications-menu">	
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-shopping-cart"></i>
		<span class="label label-warning shopcart-badge"><?= ($n > 0?$n:''); ?></span>
	</a>	
	<ul class="dropdown-menu">
		<li class="header shopcart-box"><h4 ><?= Yii::t("app","Shopping Cart")?> <span class="badge shopcart-badge"></span> <small class="pull-right"></small></h4></li>
		<li class="menu">
		<div class="shopcart-box">			
			<table class="table table-striped table-bordered">
			</table>
			<?php /*Html::a(Yii::t("app","Checkout"),["//yes/order/create"],["class"=>"btn btn-success pull-right"])*/?>
		</div>
		</li>
		<li class="footer"><?= Html::a(Yii::t("app","Checkout"),["//yes/order/create"])?></li>		
	</ul>		
</li>	

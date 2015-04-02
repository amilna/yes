<?php

use yii\helpers\Url;

$module = Yii::$app->getModule("yes");

//print_r(json_decode($model->data));
// die();
?>
<script type="text/javascript">
	
<?php $this->beginBlock('LOADORDER') ?>			

var orderdata = <?= empty($model->data)?'null':$model->data?>;

if (orderdata != null)
{	
	var o = orderdata;
	$("#order-customer_id-name").val(o.customer.name);
	$("#order-complete_reference-email").val(o.customer.email);
	$("#order-customer_id-phones").val(o.customer.phones);
	$("#order-customer_id-address").val(o.customer.address);	
	$("#order-data-note").val(o.note);	
	//console.log(JSON.parse(o.cart));
	if (typeof o.cart != "undefined") {
		createCart(JSON.parse(o.cart));
	}
	if (typeof o.shipping != "undefined") {
		var shipping = JSON.parse(o.shipping);
		var code = shipping.code;	
		findShip(code,function(){
			updateShip(shipping);
			$(".shipping-cost").each(function(){
				if ($(this).val() == o.shipping)
				{
					$(this).prop("checked",true);
				}
				else
				{
					$(this).prop("checked",false);
				}
			});
		});
	}
	if (typeof o.payment != "undefined") {
		$("#order-data-payment").val(o.payment);
	}
	
	
}
	
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['LOADORDER'], yii\web\View::POS_END);

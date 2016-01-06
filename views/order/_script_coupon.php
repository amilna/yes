<?php

use yii\helpers\Url;

$module = Yii::$app->getModule("yes");
?>
<script type="text/javascript">
	
<?php $this->beginBlock('COUPON') ?>			

function findCoupon(code,callBack)
{							
	var val = {"code":code};
	val[yii.getCsrfParam()] = yii.getCsrfToken();
	
	var url = "<?= Yii::$app->urlManager->createUrl('//yes/coupon/search')?>";
	var data = val;				
		
	var ok = function(json)
			{							
				json = JSON.parse(json);
				var total = parseFloat($(".shopcart-box h4").attr("data-total"));
				var redeem = 0;
				var price = isNaN(parseFloat(json["price"]))?0.0:Math.abs(parseFloat(json["price"]));
				var discount = isNaN(parseFloat(json["discount"]))?0.0:Math.abs(parseFloat(json["discount"]));
				if (price > 0)
				{
					redeem = price*(-1);	
				}
				else if (price <= 0 && discount > 0)
				{
					redeem = discount/100*total*(-1);
				}
				else
				{
					$("#order-complete_reference-coupon").val("");				
					alert(json["remarks"]);
				}
				
				$(".order-data-coupon").val(redeem);				
				updateVat();
				
				if (typeof callBack != "undefined")
				{
					callBack();	
				}
			};
				
	var err = function()
			{		
				$(".order-data-coupon").val(0);
				updateVat();	
			};

	ajaxPost(url,data,ok,err);		
}

$("#order-complete_reference-coupon").change(function(){
	var code = $("#order-complete_reference-coupon").val();
	findCoupon(code);
});

<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['COUPON'], yii\web\View::POS_END);

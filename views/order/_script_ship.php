<?php

use yii\helpers\Url;

$module = Yii::$app->getModule("yes");
?>
<script type="text/javascript">
	
<?php $this->beginBlock('SHIP') ?>			

function findShip(code,callBack)
{							
	var val = {"ShippingSearch[code]":code,"format":"json"};
	val[yii.getCsrfParam()] = yii.getCsrfToken();
	
	var url = "<?= Yii::$app->urlManager->createUrl('//yes/shipping/index')?>";
	var data = val;				
		
	var ok = function(json)
			{							
				json = JSON.parse(json);
				$("#order-data-city").val(json[0]["city"]+" ("+json[0]["area"]+")");
				renderShip(JSON.stringify(json[0]));
				
				if (typeof callBack != "undefined")
				{
					callBack();	
				}
			};
				
	var err = function()
			{		
				resetShip();
			};

	ajaxPost(url,data,ok,err);		
}

function renderShip(jsonstring)
{
	var shipping = JSON.parse(jsonstring);
	var ohtml = "";
	var dhtml = "";		
	var html = "<div class=\"control-group\"><label class=\"control-label bolder blue\"><?= Yii::t("app","Shipping Cost")?></label>";	
	var n = 0;		
	var cost = 0;									
	var type = JSON.parse(shipping.data);
		
	for (t in type)
	{
			var l = type[t];																
			if (l.cost > 0)
			{																						
				l["city"] = shipping.city;
				l["area"] = shipping.area;
				l["code"] = shipping.code;
				
				html += "<div class=\"radio\"><label>";
				html += "<input name=\"Order[data][shipping]\" "+(n==0?"checked ":"")+"type=\"radio\" class=\"ace shipping-cost\" value=\'"+JSON.stringify(l)+"\'>";
				html += "<span class=\"lbl\" style=\"margin-left:20px;\"> <small><b>"+l.provider+"</b> <?=Yii::t("app","cost").' '.$module->currency["symbol"]?>"+toMoney(l.cost)+"/kg ("+l.remarks+")</small></span>";
				html += "</label></div>";								
																				
				if (n == 0)
				{
					updateShip(l);
				}
				
				n += 1;
			}
			
	}
	html += "</div>";	
	$(".radio-shipping-cost").html(html);
	$(".shipping-cost").click(function(){
		var l = JSON.parse($(this).val());
		updateShip(l);
	});	
}

function resetShip()
{
	$(".radio-shipping-cost").html("");
	$("#order-shippingcost-label").html("");
	$("#order-data-shippingcost").val(0);
	$("#order-data-city").val("");
	updateVat();	
}

function updateShip(l)
{
	var berat = parseFloat($("#shopcart-box h4").attr("data-weight"));
	var cost = l.cost*(isNaN(berat)?0:berat);
	if (cost > 0)
	{
		ohtml = "<h4 data-unitcost="+l.cost+"><?=Yii::t("app","Shipping Cost")?><small class=\"pull-right\" >Total <?=$module->currency["symbol"]?>"+toMoney(cost)+"</small></h4>";
		ohtml += "<div class=\"row\"><div class=\"col-xs-10\"><b>"+l.provider+"</b> <?=Yii::t("app","destination to ")?>"+l.city+" ("+l.area+")"+", <?=Yii::t("app","cost").' '.$module->currency["symbol"]?>"+toMoney(l.cost)+"/kg ("+l.remarks+")</div></div>";	
	}
	else
	{
		ohtml = "";	
	}
	$("#order-shippingcost-label").html(ohtml);
	$("#order-data-shippingcost").val(cost);
	
	updateVat();
}

$("#order-data-city").focusout(function() {
	var berat = parseFloat($("#shopcart-box h4").attr("data-weight"));	
	if ($(".radio-shipping-cost").html() == "" && berat > 0)
	{		
		resetShip();
	}	
});
	
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['SHIP'], yii\web\View::POS_END);

<?php

use yii\helpers\Url;

$module = Yii::$app->getModule("yes");
?>
<script type="text/javascript">

<?php $this->beginBlock('ASCI') ?>			

function deAsci(a) {
	var b = a.split("-");
	var c = '';
	for(var i=0;i<b.length;i++) {
		c += String.fromCharCode(parseInt(b[i]));
	}
	return c;
}

function enAsci(a,s) {	
	var c = '';
	for(var i=0;i<a.length;i++) {
		c += ((c=="") || (typeof s == "undefined")?"":"-")+(a[i].charCodeAt());
	}	
	return c;
}
<?php $this->endBlock(); ?>	

<?php $this->beginBlock('AJAX_POST') ?>			
	function ajaxPost(url,data,ok,err,diverr)
	{			
		$.ajax({
			type:"POST",
			url:url,
			data: data, 		
			/*
			xhrFields: {
				// The "xhrFields" property sets additional fields on the XMLHttpRequest.
				// This can be used to set the "withCredentials" property.
				// Set the value to "true" if you\'d like to pass cookies to the server.
				// If this is enabled, your server must respond with the header
				// "Access-Control-Allow-Credentials: true".
				withCredentials: false
			  },			
			*/ 
			success: function(json) 
					{ 
						ok(json);					
					},
			error: function(e, ts, et) 
					{ 
						if (typeof err != "undefined")
						{
							err();
						}
						else
						{
							var psn = "<?= Yii::t("app","Please check connection!")?>";						
							//alert(psn);													
						}
					}
		});
		
	}
<?php $this->endBlock(); ?>	
	
<?php $this->beginBlock('SHOPCART') ?>			
	function toMoney(val)
	{
		return parseFloat(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,").replace(/\.00/g,"").replace(/\,/g,"<?= $module->currency["thousand_separator"]?>");
	}
	
	function updateItem(val,idata,qty){	
		
		var type = "add";
		if (typeof val == "undefined")
		{
			var val = {"shopcart":{"data":{} }};
			if (typeof qty != "undefined")
			{										
				val["shopcart"]["data"] = {"quantity":qty};
			}
			val["shopcart"]["data"]["idata"] = idata;
			type = "update";
		}
		val[yii.getCsrfParam()] = yii.getCsrfToken();
		
		var url = "<?= Yii::$app->urlManager->createUrl('//yes/order/shopcart')?>";
		var data = val;		
		
		var order = "<p class=\'alert alert-warning\'><?= Yii::t("app","Add to shopcart failed, try again later")?></p>";
		
		var ok = function(json)
					{											
						json = jQuery.parseJSON(json);	
						if (json)
						{																
							if (json.status == 1)
							{																																																					
								if ($.isArray(shopcart))
								{
									shopcart = {};	
								}
								shopcart[idata] = val["shopcart"]["data"];								
								createCart(shopcart);								
							}
							else if (json.status == 2)
							{																								
								shopcart[idata]["quantity"] = parseInt(shopcart[idata]["quantity"])+parseInt(val["shopcart"]["data"]["quantity"]);
								createCart(shopcart);														
							}
							else if (json.status == 3)
							{								
								delete shopcart[idata];
								createCart(shopcart);							
							}
						}						
					};
		
		var err = function()
					{											
					};
				
		ajaxPost(url,data,ok,err);
	}			
	
	function updateTotal()
	{
		var data = shopcart;
		var defvat = <?=$module->defaults["vat"]?$module->defaults["vat"]:0?>;
		var weight = 0;
		var vat = 0;
		var total = 0;
		var n = 0;
		
		$.each(data,function(i,d){			
			n += 1;
			total += parseFloat(d["price"])*parseFloat(d["quantity"]);	
			weight += parseFloat(d["quantity"])*parseFloat(d["data_weight"]);			
			var aktvat = (typeof d["data_vat"] == "undefined"?defvat:parseFloat(d["data_vat"]));
			vat += parseFloat(d["quantity"])*aktvat*parseFloat(d["price"]);
			
		});						
		
		$(".shopcart-box h4 small").html("Total <?= $module->currency["symbol"]?>"+toMoney(total));		
		$(".shopcart-badge").html(n > 0?n:"");
		$(".shopcart-box h4").attr("data-weight",weight);
		$(".shopcart-box h4").attr("data-total",total);
		$(".shopcart-box h4").attr("data-vat",vat);
		
		/* update ship cost */
		var unitcost = parseFloat($("#order-shippingcost-label h4").attr("data-unitcost"));
		var cost = unitcost*weight;
		$(".order-shippingcost-label h4 small").html("Total <?=$module->currency["symbol"]?>"+toMoney(cost));				
		$(".order-data-shippingcost").val(cost);
		
		/* update vat */
		updateVat();
	}	

	function updateVat()
	{			
		var total = parseFloat($(".shopcart-box h4").attr("data-total"));
		var shipcost = parseFloat($(".order-data-shippingcost").val());
		shipcost = (isNaN(shipcost)?0:shipcost);
		
		/*		
		//console.log(total,shipcost);
		var defvat = <?=$module->defaults["vat"]?$module->defaults["vat"]:0?>;
		var vat = (defvat?(total+shipcost)*defvat:0);	
		*/
		
		var vat = parseFloat($(".shopcart-box h4").attr("data-vat"));
		
		var html = "";	
		if (vat > 0)
		{
			html = "<h4><?=Yii::t("app","VAT")." <small>(".Yii::t("app","Value Added Tax").")</small> <small class='pull-right'>Total ".$module->currency["symbol"]?>"+toMoney(vat)+"</small></h4>";
			$(".order-data-vat").val(vat);
		}	
		var grand = total+shipcost+vat;
		var gtml = "<h3><?=Yii::t("app","Total Payment")." <small class='pull-right'>Grand Total ".$module->currency["symbol"]?>"+toMoney(grand)+"</small></h3>";	
		$(".order-vat-label").html(html);	
		$(".order-grandtotal-label").html(gtml);
		$(".order-total").val(grand);
		
		var berat = Math.ceil(parseFloat($(".shopcart-box h4").attr("data-weight")));
		if (berat <= 0)
		{
			$(".radio-shipping-cost").addClass("hidden");
		}
	}	
	
	function removeCart(d)
	{		
		$("#remove_itemcart_"+d["idata"]).parent().parent().parent().parent().remove();		
	}
	
	function renderCart(d)
	{
		var html = "";
		var id = d["id"];
		var title = d["title"];
		var image = d["image"];									
		var price = d["price"];
		var weight = <?= $module->defaults["weight"] ?>;
		var vat = <?= $module->defaults["vat"] ?>;
		var quantity = d["quantity"];									
		var datas = {};
		var remarks = "";		
		for (key in d)
		{
			if (key.substr(0,5) == "data_") {
				datas[key.replace("data_","")] = d[key];
				if (key.replace("data_","") == "weight")
				{
					weight = parseFloat(d[key]);
					weight = (isNaN(weight)?<?= $module->defaults["weight"] ?>:weight);
				}
				else if (key.replace("data_","") == "vat")
				{
					vat = parseFloat(d[key]);
					vat = (isNaN(vat)?<?= $module->defaults["vat"] ?>:vat);
				}
				else
				{
					remarks += (remarks == ""?"":", ")+key.replace("data_","")+": "+d[key];				
				}	
			}
		}
		
		var idata = d["idata"];		
		
		html += "<tr><td style=\"vertical-align:middle;\">";
		html += "	<div class=\"media\"><div class=\"media-left media-middle\"><img class=\"media-object\" src=\""+image+"\" style=\"margin-right:4px;max-width:44px;float:left;\"></div>";
		html += "	<div class=\"media-body\"><h6>"+title+" <small>"+remarks+"</small></h6>";											
		html += "	<div class='input-group'>";		
		html += "		<div class=\"input-group-addon\" style=\"background:#fff\"><?= $module->currency["symbol"]?>"+toMoney(price)+" x </div>";
		html += "		<input type=\"number\" class=\"form-control quantity_itemcart\" data-price="+price+" data-weight="+weight+" data-vat="+vat+" id=\"quantity_itemcart_"+idata+"\" min=\"1\" max=\"999\" value=\""+quantity+"\"/>";
		html += "		<div id=\"remove_itemcart_"+idata+"\" class=\"remove_itemcart input-group-addon danger\" title=\"<?= Yii::t('app','Remove Item')?>\" style=\"cursor:pointer;\"><i class=\"glyphicon glyphicon-trash\"></i></div>";
		html += "	</div></div></div>";								
		html += "</td></tr>";													
		return html;
	}
	
	function createCart(data)
	{						
		if (!(JSON.stringify(data) == "[null]" || data == null))
		{			
			var html = "";				
			$(".shopcart-box .table").html(html);
			$.each(data, function(id,d)
			{											
				html += renderCart(d);																	
			});																																							
			$(".shopcart-box .table").html(html);
			
			updateTotal();										
			
			$(".shopcart-box .table .quantity_itemcart").change(function(){
				var id = $(this).attr("id").replace("quantity_itemcart_","");				
				var qty = $(this).val()-shopcart[id]["quantity"];				
				updateItem(undefined,id,qty);
			});
			
			$(".shopcart-box .table .remove_itemcart").click(function(){
				var id = $(this).attr("id").replace("remove_itemcart_","");									
				updateItem(undefined,id);										
			});	
			
			$(".shopcart-box .table .remove_itemcart,.shopcart-box .table .quantity_itemcart").click(function(e){        				
				e.stopPropagation();
			});			
			
		}
	}
	
	$(".order_itemcart").click(function()
	{
		var id = parseInt($(this).attr("id").replace("order_itemcart_",""));
		var d = {};
		var idata = id;		
		$(".item-shopcart").each(
			function(v,i)
			{				
				var atr = $(i).attr("id");				
				d[atr] = $(i).val();
				if (atr.substr(0,5) == "data_") {					
					idata += $(i).val();
				}				
			}
		);	
		var idata = enAsci(idata);
		d["idata"] = idata;								
		var val = {"shopcart":{}};
		val["shopcart"]["data"] = d;								
		
		if ($("#quantity_itemcart_"+d["idata"]).length > 0)
		{
			$("#quantity_itemcart_"+d["idata"]).val(parseFloat($("#quantity_itemcart_"+d["idata"]).val())+parseFloat(d["quantity"]));
			updateItem(undefined,d["idata"],d["quantity"]);
		}
		else
		{			
			updateItem(val,idata);		
		}
	});
	
	var shopcart = <?= json_encode(Yii::$app->session->get('YES_SHOPCART'))?>;	
	createCart(shopcart);			
	
<?php $this->endBlock(); ?>


<?php $this->beginBlock('JS_READY') ?>
   
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['ASCI'], yii\web\View::POS_END);
$this->registerJs($this->blocks['AJAX_POST'], yii\web\View::POS_END);
$this->registerJs($this->blocks['SHOPCART'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

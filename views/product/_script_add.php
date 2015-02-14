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
	
<?php $this->beginBlock('JS_END') ?>			
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
								addCart(val["shopcart"]["data"]);
								updateTotal();
							}
							else if (json.status == 2)
							{
								updateTotal();								
							}
							else if (json.status == 3)
							{
								removeCart(val["shopcart"]["data"]);
								updateTotal();
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
		var total = 0;
		var n = 0;
		$(".quantity_itemcart").each(function(n,d){			
			total += $(d).val()*parseFloat($(d).attr("data-price"));	
			n = n;
		});		
		
		$("#shopcart-box h4 small").html("Total <?= $module->currency["symbol"]?>"+toMoney(total));
		$("#shopcart-badge").html(n > 0?n:"");
	}	
	
	function addCart(d)
	{
		var html = renderCart(d);
		$("#shopcart-box .table").append(html);
		
		$("#quantity_itemcart_"+d["idata"]).change(function(){
			var id = $(this).attr("id").replace("quantity_itemcart_","");
			var qty = $(this).val();			
			updateItem(undefined,id,qty);
		});
		
		$("#remove_itemcart_"+d["idata"]).click(function(){
			var id = $(this).attr("id").replace("remove_itemcart_","");									
			updateItem(undefined,id);										
		});		
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
		var quantity = d["quantity"];									
		var datas = {};
		var remarks = "";		
		for (key in d)
		{
			if (key.substr(0,5) == "data_") {
				datas[key.replace("data_","")] = d[key];
				remarks += (remarks == ""?"":", ")+key.replace("data_","")+": "+d[key];
				
			}
		}
		
		var idata = d["idata"];		
		
		html += "<tr><td style=\"vertical-align:middle;\">";
		html += "	<div class=\"media\"><div class=\"media-left media-middle\"><img class=\"media-object\" src=\""+image+"\" style=\"margin-right:4px;max-width:44px;float:left;\"></div>";
		html += "	<div class=\"media-body\"><h6>"+title+" <small>"+remarks+"</small></h6>";											
		html += "	<div class='input-group'>";		
		html += "		<div class=\"input-group-addon\" style=\"background:#fff\"><?= $module->currency["symbol"]?>"+toMoney(price)+" x </div>";
		html += "		<input type=\"number\" class=\"form-control quantity_itemcart\" data-price="+price+" id=\"quantity_itemcart_"+idata+"\" min=\"1\" max=\"999\" value=\""+quantity+"\"/>";
		html += "		<div id=\"remove_itemcart_"+idata+"\" class=\"remove_itemcart input-group-addon danger\" title=\"<?= Yii::t('app','Remove Item')?>\" style=\"cursor:pointer;\"><i class=\"glyphicon glyphicon-trash\"></i></div>";
		html += "	</div></div></div>";								
		html += "</td></tr>";													
		return html;
	}
	
	function createCart(data)
	{				
		var html = "";				
		$.each(data, function(id,d)
		{											
			html += renderCart(d);																	
		});																
																								
		$("#shopcart-box .table").html(html);
		updateTotal();										
		
		$("#shopcart-box .table .quantity_itemcart").change(function(){
			var id = $(this).attr("id").replace("quantity_itemcart_","");
			var qty = $(this).val();			
			updateItem(undefined,id,qty);
		});
		
		$("#shopcart-box .table .remove_itemcart").click(function(){
			var id = $(this).attr("id").replace("remove_itemcart_","");									
			updateItem(undefined,id);										
		});				
		
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
			updateItem(val);		
		}
	});
	
	var shopcart = <?= json_encode(Yii::$app->session->get('YES_SHOPCART'))?>;
	if (shopcart != null)
	{
		createCart(shopcart);
	}		
	
<?php $this->endBlock(); ?>


<?php $this->beginBlock('JS_READY') ?>
   
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['ASCI'], yii\web\View::POS_END);
$this->registerJs($this->blocks['AJAX_POST'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

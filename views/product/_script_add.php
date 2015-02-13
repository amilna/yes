<?php

use yii\helpers\Url;

$module = Yii::$app->getModule("yes");
?>
<script type="text/javascript">

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
	function addItem(val)
	{				
		var url = "<?= Yii::$app->urlManager->createUrl('//yes/product/add')?>";
		var data = val;		
		
		var order = "<p class=\'alert alert-warning\'><?= Yii::t("app","Add to shopcart failed, try again later")?></p>";
		
		var ok = function(json)
					{											
						json = jQuery.parseJSON(json);	
						if (json)
						{																
							if (json.status == 1)
							{																																																					
								renderCart(json.data);															
							}													
						}						
					};
		
		var err = function()
					{											
					};
				
		ajaxPost(url,data,ok,err);
	}
	
	function updateItem(id,qty){	
		
		var val = {"shopcart":{"data":{} }};
		if (typeof qty != "undefined")
		{										
			val["shopcart"]["data"] = {"quantity":qty};
		}
		val["shopcart"]["data"]["id"] = id;
		
		var url = "<?= Yii::$app->urlManager->createUrl('//yes/product/add')?>";
		var data = val;		
		
		var order = "<p class=\'alert alert-warning\'><?= Yii::t("app","Add to shopcart failed, try again later")?></p>";
		
		var ok = function(json)
					{											
						json = jQuery.parseJSON(json);	
						if (json)
						{																
							if (json.status == 1)
							{																																																					
								renderCart(json.data);															
							}													
						}						
					};
		
		var err = function()
					{											
					};
				
		ajaxPost(url,data,ok,err);
	}	
	
	function toMoney(val)
	{
		return parseFloat(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,").replace(/\.00/g,"").replace(/\,/g,"<?= $module->currency["thousand_separator"]?>");
	}
	
	function renderCart(data)
	{				
		var html = "";
		var total = 0;
		var n = 0;
		$.each(data, function(id,d)
		{								
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
			
			html += "<tr><td rowspan=2 style=\"vertical-align:middle;\"><img src=\""+image+"\" style=\"padding:2px;max-width:44px;\"></td>";
			html += "<td >"+title+"</td></tr>";			
			html += "<tr>";			
			html += "	<td >";			
			html += "		<div class='row-fluid' >";				  			
			html += "		<div class='input-group col-xs-12'>";				  
			//html += "		<?= $module->currency["symbol"]?>"+toMoney(price)+" <small style=\"color:#adadad;\">x</small>";					
			html += "		  <div class=\"input-group-addon\" style=\"background:#fff\"><?= $module->currency["symbol"]?>"+toMoney(price)+" x </div>";
			html += "		  <input type=\"number\" class=\"form-control quantity_itemcart\" id=\"quantity_itemcart_"+id+"\" min=\"1\" max=\"999\" value=\""+quantity+"\"/>";
			html += "		  <div id=\"remove_itemcart_"+id+"\" class=\"remove_itemcart input-group-addon danger\" title=\"<?= Yii::t('app','Remove Data')?>\" style=\"cursor:pointer;\">x</div>";
			html += "		</div>";						
			html += "		</div>";		
			html += "	</td>";						
			html += "</tr>";								
			
			total += quantity*price;
			n += 1;
		});																
		
		$(".cara_order li").attr("class","");
		$("#cara_order_3").attr("class","active");		
				
		$("#shopcart-badge").html(n > 0?n:"");
		
		var isopen = $("#shopcart-btn").attr("class") == "btn btn-app btn-xs btn-success shopcart-btn open"?true:false;								
		if (isopen)
		{																		
			if (n > 0)
			{
				$(".cara_order li").attr("class","");
				$("#cara_order_4").attr("class","active");							
			}
		}								
															
		$("#shopcart-box .table").html(html);								
		$("#shopcart-box h4 small").html("Total <?= $module->currency["symbol"]?>"+toMoney(total));
		
		$("#shopcart-box .table .quantity_itemcart").change(function(){
			var id = parseInt($(this).attr("id").replace("quantity_itemcart_",""));
			var qty = $(this).val();
			updateItem(id,qty);
		});
		
		$("#shopcart-box .table .remove_itemcart").click(function(){
			var id = parseInt($(this).attr("id").replace("remove_itemcart_",""));									
			updateItem(id);										
		});				
		
	}
	
	$(".order_itemcart").click(function()
	{
		var id = parseInt($(this).attr("id").replace("order_itemcart_",""));
		var data = {};
		$(".item-shopcart").each(
			function(v,i)
			{				
				var atr = $(i).attr("id");				
				data[atr] = $(i).val();				
			}
		);	
		var val = {"shopcart":{}};
		val["shopcart"]["data"] = data;				
		addItem(val);		
	});
	
	var shopcart = <?= json_encode(Yii::$app->session->get('YES_SHOPCART'))?>;
	renderCart(shopcart);
	
<?php $this->endBlock(); ?>


<?php $this->beginBlock('JS_READY') ?>
   
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['AJAX_POST'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

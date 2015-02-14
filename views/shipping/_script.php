<?php

use yii\helpers\Url;
?>
<script type="text/javascript">
<?php $this->beginBlock('JS_END') ?>		
		var data = <?= empty($model->data)?"{}":$model->data ?>;				
		
		function renderFormDetails(d)
		{	
			var xhr = $("#template_form_details").html();
														
			var n = $(".detail").length;
			
			$(".detail").each(function(){
				var n0 = $(this).attr("id").replace("detail_","");
				if (n0 != ":N")
				{
					n = Math.max(n,parseInt(n0));
				}
			});
			
			xhr = xhr.replace(/:N/g,n);
			$(".data").append(xhr);									
						
			if (typeof d !== "undefined")
			{				
				$("#Shipping_data_"+n+"_provider").val(typeof d["provider"] !== "undefined"?d["provider"]:"");
				$("#Shipping_data_"+n+"_cost").val(typeof d["cost"] !== "undefined"?d["cost"]:"");
				$("#Shipping_data_"+n+"_remarks").val(typeof d["remarks"] !== "undefined"?d["remarks"]:"");				
			}									
			
			$("#data-del"+n).bind("click",function(){
				deleteData(n);
			});			
		}	
		
		function deleteData(n) {
			$("#detail_"+n).html("");			
		}
		
		$("#data-add").bind("click",function(){
			renderFormDetails();
		});
		
		for (i in data)
		{
			var d = data[i];								
			renderFormDetails(d);
		}		
				
		
		
<?php $this->endBlock(); ?>


<?php $this->beginBlock('JS_READY') ?>
   
<?php $this->endBlock(); ?>

</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);

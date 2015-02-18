<?php

use yii\helpers\Url;
?>
<script type="text/javascript">
<?php $this->beginBlock('JS_END') ?>		
		var addresses = <?= empty($model->addresses)?"{}":$model->addresses ?>;				
		
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
			$(".addresses").append(xhr);									
						
			if (typeof d !== "undefined")
			{				
				$("#Customer_addresses_"+n+"").val(typeof d !== "undefined"?d:"");				
			}									
			
			$("#address-del"+n).bind("click",function(){
				deleteData(n);
			});			
		}	
		
		function deleteData(n) {
			$("#detail_"+n).html("");			
		}
		
		$("#address-add").bind("click",function(){			
			renderFormDetails();
		});
		
		for (i in addresses)
		{
			var d = addresses[i];								
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

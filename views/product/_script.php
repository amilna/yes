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
			
			$("#w0"+n+"").val(0);
			if (typeof d !== "undefined")
			{
				$("#w0"+n+"").val(typeof d["type"] !== "undefined"?d["type"]:false);
				$("#Product_data_"+n+"_label").val(typeof d["label"] !== "undefined"?d["label"]:"");
				$("#Product_data_"+n+"_value").val(typeof d["value"] !== "undefined"?d["value"]:"");				
			}			
			
			var select2_x = {"allowClear":false,"width":"resolve","theme":"krajee"};			
			jQuery("#w0"+n).prepend("<option val></option>");
			jQuery.when(jQuery("#w0"+n).select2(select2_x)).done(initSelect2Loading("w0"+n));
			jQuery("#w0"+n).on("select2-open", function(){
				initSelect2DropStyle("w0"+n);				
			});												
			
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

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use kartik\money\MaskMoney;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\datetime\DateTimePicker;

use amilna\yes\models\Payment;

/* @var $this yii\web\View */
/* @var $model amilna\cap\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Invoice '.$model->reference;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$data = json_decode($model->data);
$customer = $data->customer;
$shipping = isset($data->shipping)?json_decode($data->shipping):null;
$cart = isset($data->cart) && $data->cart != "null"?json_decode($data->cart):[];
$payment = isset($data->payment)?Payment::findOne($data->payment):false;

$module = Yii::$app->getModule('yes');

$company = '<strong>'.$module->company["name"].'</strong><br>
		'.$module->company["address"].'<br>
		'.Yii::t("app","Phones").': '.$module->company["phone"].'<br/>
		'.Yii::t("app","Email").': <a href="mailto:'.$model->toHex($module->company["email"]).'">'.str_replace("@"," [AT] ",$module->company["email"]).'</a>';

$subject = '<strong>'.Html::encode($customer->name).'</strong><br>
		'.Html::encode($customer->address).'<br>
		'.($shipping != null?Html::encode($shipping->city.', '.$shipping->area).'<br>':'').'
		'.Yii::t("app","Phones").': '.Html::encode($customer->phones).'<br/>
		'.(isset($customer->email)?Yii::t("app","Email").': <a href="mailto:'.$model->toHex($customer->email).'">'.str_replace("@"," [AT] ",$customer->email):"").'</a>';
?>

<h1><small><?= Html::encode($this->title) ?></small></h1>

<section class="invoice">
<!-- title row -->
  <div class="row">
	<div class="col-xs-12">
	  <h2 class="page-header">
		<i class="fa fa-globe"></i> <?= $module->company["name"]?>
		<small class="pull-right"><?= Html::encode(date('r',strtotime($model->time))) ?></small>
	  </h2>
	</div><!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
	<div class="col-sm-4 invoice-col">
	  <?= Yii::t("app","From")?>
	  <address>
		<?= $company ?>	
	  </address>
	</div><!-- /.col -->
	<div class="col-sm-4 invoice-col">
	  <?= Yii::t("app","To")?>
	  <address>
		<?= $subject ?>
	  </address>
	</div><!-- /.col -->
	<div class="col-sm-4 invoice-col">
	  <small class="btn btn-warning pull-right"><?= $model->itemAlias("status",$model->status)?></small>	
	  <br/>
	  <br/>
	  <b><?= Yii::t("app","Invoice") ?>: </b> <?= Html::encode($model->reference) ?><br/>	  
	  <b><?= Yii::t("app","Payment Due") ?>: </b> <?= date('r',strtotime($model->time)+(60 * 60 * 24 * 1)) ?><br/>
	  	  
	</div><!-- /.col -->
  </div><!-- /.row -->

  <!-- Table row -->
  <div class="row">
	<div class="col-xs-12 table-responsive">
	  <table class="table table-striped">
		<thead>
		  <tr>
			<th>No</th>
			<th><?=Yii::t("app","Title")?></th>
			<th><?=Yii::t("app","Remarks")?></th>
			<th style="text-align:right"><?= Yii::t("app","Quantity")?></th>
			<th style="text-align:right;min-width:120px;"><?= Yii::t("app","Unit Price")?></th>
			<th style="text-align:right;min-width:120px;"><?= Yii::t("app","Total")?></th>
		  </tr>
		</thead>
		<tbody>
		<?php
			$n = 0;		
			
			foreach ($cart as $p)
			{				
				if ($p != null)
				{
					$n += 1;
					$title = Html::encode($p->title);
					$remarks = "";
					foreach ($p as $k=>$v)
					{
						if (substr($k,0,5) == "data_" && !in_array(substr($k,5),["weight","vat"]))
						{
							$remarks .= ($remarks == ""?"":", ").substr(Html::encode($k),5).": ".Html::encode($v);
						}
					}																			
					
					echo '<tr>
							<td>'.$n.'</td>
							<td>'.$title.'</td>
							<td>'.$remarks.'</td>
							<td style="text-align:right">'.$p->quantity.'</td>
							<td style="text-align:right">'.$model->toMoney($p->price,0).'</td>
							<td style="text-align:right">'.$model->toMoney($p->quantity*$p->price,0).'</td>
						</tr>';
				}
			}
					
		?>				
		</tbody>
	  </table>
	</div><!-- /.col -->
  </div><!-- /.row -->


  <div class="row">
	<!-- accepted payments column -->
	<div class="col-xs-6">
	  <p class="lead"><?= Yii::t("app","Remarks")?></p>	  
	  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
		<?php if ($payment) { ?> 		
		<?= Yii::t("app","for")?>
		<?= Yii::t("app","Products buying in this website, payment via")?>				
		<?= "<b>".Html::encode($payment->terminal)."</b> ".Yii::t("app","account ")."<b>".Html::encode($payment->account)."</b> ".Yii::t("app","in the name of ")."<b>".Html::encode($payment->name)."</b>" ?>
		<br />
		<br />
		<?php } ?>
		<strong><?= Yii::t("app","Order Remarks")?></strong>	  
		<br />
		<?= Html::encode($data->note) ?>
	  </p>
	  <?= Html::a(Yii::t('app', 'Create {modelClass}', [
			'modelClass' => Yii::t('app', 'Confirmation'),
		]), ['//yes/confirmation/create','reference'=>$model->reference], ['class' => 'btn btn-success']) ?>
	</div><!-- /.col -->
	<div class="col-xs-6">	  
	  <p class="lead"><?= Yii::t("app","Payment Due") ?> <?= date('r',strtotime($model->time)+(60 * 60 * 24 * 1)) ?></p>
	  <div class="table-responsive">
		<table class="table">
		  <tr>
			<th style="width:50%">Subtotal:</th>
			<td style="text-align:right"><?= $model->toMoney($model->total-($data->shippingcost+$data->vat),0)?></td>
		  </tr>
		  <?php
			if ($data->vat > 0)
			{				
				echo '<tr>
						<th style="width:50%"><h5>'.Yii::t("app","VAT").' <small>'.Yii::t("app","Value Added Tax").'</small> '.($module->defaults["vat"]*100).'%</h5></th>
						<td style="text-align:right;vertical-align:middle;">'.$model->toMoney($data->vat,0).'</td>
					</tr>';
			}
					  
			if ($shipping != null && $data->shippingcost > 0)
			{				
				echo '<tr>
						<th style="width:50%"><h5>'.Yii::t("app","Shipping Cost").' <small><i>'.$shipping->provider.' '.$shipping->code.'</i> '.Yii::t("app","destination ").$shipping->city.' ('.$shipping->area.')</small> '.$model->toMoney($data->shippingcost/$shipping->cost,2,false).' Kg</h5></th>
						<td style="text-align:right">'.$model->toMoney($data->shippingcost,0).'</td>
					</tr>';
			}
		  ?>
		  		  		   
		  <tr>
			<th style="width:50%">Total:</th>
			<td style="text-align:right"><?= $model->toMoney($model->total,0)?></td>
		  </tr>
		</table>
	  </div>
	</div><!-- /.col -->
  </div><!-- /.row -->

  <!-- this row will not appear when printing 
  <div class="row no-print">
	<div class="col-xs-12">
	  <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
	  <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
	  <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
	</div>
  </div>
  -->
</section><!-- /.content -->

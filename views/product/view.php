<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use amilna\yes\models\Category;
use himiklab\colorbox\Colorbox;
use amilna\elevatezoom\ElevateZoom;

use kartik\widgets\Select2;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag(['name' => 'title', 'content' => Html::encode($model->title)]);
$this->registerMetaTag(['name' => 'description', 'content' => Html::encode($model->description)]);

$cat = new Category();
$module = Yii::$app->getModule("yes");
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>    

	<div class="row">
		<!-- Product -->
		<div class="col-sm-8 panel">
			<div class="panel-body">
				<div>
					<h3><?= Html::encode($model->author?$model->author->username:"") ?> <small><i class="glyphicon glyphicon-time"></i>  <?= date('D d M, Y',strtotime($model->time)) ?> </small></h3>
				</div>				
				<div>
					<div class="row">	
						<div class="col-sm-6">
							
					<?php
						$images = null;
						if ($model->images != null)
						{
							$images = json_decode($model->images);
							/*
							foreach ($images as $i) {
								echo '<div class="col-md-4"><div class="thumbnail">';
								echo Html::a(Html::img(str_replace("/upload/","/upload/.thumbs/",$i),["style"=>""]),$i,["class"=>"colorbox"]);								
								echo '</div></div>';
							}
							*/ 							
							
							echo ElevateZoom::widget([
								'images'=>$images,
								'baseUrl'=>$module->uploadURL,
								'smallPrefix'=>'/.thumbs',
								'mediumPrefix'=>'',
								'options'=>[		
									'zoomType'=> "lens", 
									'containLensZoom'=> false,		
									'borderSize'=>0,
									'scrollZoom'=> true, 
									'gallery'=>'galez',		
									'cursor'=>'crosshair',			
								]
							]);
						}
						
						
					?>					
						<br>	
						</div>
						
						<?= Html::encode($model->description) ?>
						<div id="item-shopcart-<?= $model->id ?>" class="well col-sm-6 pull-right" style="margin-top:20px">
														
							<?php 
								$price = ($model->discount > 0?$model->price-($model->price*$model->discount/100):$model->price);
								if ($price > 0) {
									echo "<h3>".Html::encode($model->toMoney($price))."</h3>";							
								}
							?>
							<?php 								
								if ($model->discount > 0) {
									echo '<h4 class="label label-danger" style="text-decoration: line-through;">'.Html::encode($model->toMoney($model->price)).'</h4>';							
								}
							?>
							<hr>
							<?php
							echo Html::hiddenInput('Orders[product_id]',$model->id,["id"=>"id","class"=>"item-shopcart"]);
							echo Html::hiddenInput('Orders[product_title]',$model->title,["id"=>"title","class"=>"item-shopcart"]);
							echo Html::hiddenInput('Orders[product_image]',($images == null?null:str_replace($module->uploadURL."/",$module->uploadURL."/.thumbs/",$images[0])),["id"=>"image","class"=>"item-shopcart"]);
							echo Html::hiddenInput('Orders[product_price]',$price,["id"=>"price","class"=>"item-shopcart"]);
							
							echo '<div class="form-group'.($price > 0?"":" hidden").'"><label class="control-label">'.Yii::t("app","Quantity").'</label>';
							echo TouchSpin::widget([
										'name' => 'Orders[product_qty]',
										'value' => 1,
										'options' => ["id"=>"quantity",'class'=>'item-shopcart'],
										'pluginOptions'=>[
											'min'=>1,												
											'step'=>1,
											'max'=>100000000,
											'boostat'=> 10,
											'maxboostedstep'=> 100000,
											'handle'=>'triangle',
											'tooltip'=>'always'
										]
									]);
							echo '</div>';																
						
							$data = json_decode($model->data);							
							foreach ($data as $d) {
								$type = $d->type;																
								if ($type == 0)
								{
									$options = [];
									$deval = "";
									foreach (explode(",",$d->value) as $v)
									{
										$options[trim($v)] = trim($v);	
										$deval = $deval == ""?trim($v):$deval;
									}
									
									echo '<div class="form-group"><label class="control-label">'.Html::encode($d->label).'</label>';
									echo Select2::widget([
										'name' => 'Orders[data]['.$d->label.']', 
										'data' => $options,
										'value' => $deval,
										'options' => [
											'placeholder' => Yii::t('app','Select ').$d->label,
											'class'=>'item-shopcart',
											"id"=>"data_".str_replace(" ","_",$d->label),
										],
									]);	
									echo '</div>';
								}
								else if ($type == 1)
								{
									echo '<div class="form-group"><label class="control-label">'.Html::encode($d->label).'</label>';
									echo Html::textInput('Orders[data]['.$d->label.']',$d->value,["id"=>"data_".str_replace(" ","_",$d->label),"class"=>"form-control item-shopcart","placeholder"=>Yii::t("app",$d->label),"style"=>"width:100%"]);
									echo '</div>';	
								}
								else if ($type == 2)
								{
									echo '<div class="form-group"><label class="control-label">'.Html::encode($d->label).'</label>';
									echo TouchSpin::widget([
											'name' => 'Orders[data]['.$d->label.']', 										
											'value' => ($d->value == null?0:$d->value),
											'options'=>["id"=>"data_".str_replace(" ","_",$d->label),"class"=>"item-shopcart"],
											'pluginOptions'=>[
												'min'=>1,												
												'step'=>1,
												'max'=>100000000,
												'boostat'=> 10,
												'maxboostedstep'=> 100000,
												'handle'=>'triangle',
												'tooltip'=>'always'
											]
										]);									
									echo '</div>';	
								}
								else if ($type == 3)
								{
									echo '<div class="form-group"><label class="control-label">'.Html::encode($d->label).'</label>';
									echo Html::textArea('Orders[data]['.$d->label.']',$d->value,["id"=>"data_".str_replace(" ","_",$d->label),"class"=>"form-control item-shopcart","placeholder"=>Yii::t("app",$d->label),"style"=>"width:100%"]);
									echo '</div>';
								}
								else if ($type == 4)
								{
									echo '<div class="form-group"><label class="control-label">'.$d->value.'</label>';
									echo Html::hiddenInput('Orders[data]['.$d->label.']',$d->value,["id"=>"data_".str_replace(" ","_",$d->label),"class"=>"form-control item-shopcart"]);
									echo '</div>';
								}							
								else if ($type == 5)
								{
									echo Html::hiddenInput('Orders[data]['.$d->label.']',$d->value,["id"=>"data_".str_replace(" ","_",$d->label),"class"=>"form-control item-shopcart"]);
								}
							}							
							?>
							<a id="order_itemcart_<?=$model->id?>" class="btn btn-primary order_itemcart"><?= Yii::t("app","Add to Cart")?></a>
						</div>
													
						<?= HtmlPurifier::process($model->content) ?>
					</div>	
				</div>		
			</div>
		</div>
		<!-- End Product -->
		<!-- Sidebar -->
		<div class="col-sm-4">
			<?php /*
			<p>
				<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
			</p>
			*/ ?> 
			<h4><?= Yii::t("app","Search our Products")?></h4>
			
			<form action="<?=Yii::$app->urlManager->createUrl("//yes/product/index")?>" method="get">
				<div class="input-group">
					<input class="form-control input-md" name="term" id="appendedInputButtons" type="text">
					<span class="input-group-btn">
						<button class="btn btn-md" type="submit"><?= Yii::t("app","Search")?></button>
					</span>
				</div>
			</form>
			<hr>
			<h4><?= Yii::t("app","Recent Products")?></h4>
			<ul class="list-unstyled">
				<?php
					foreach ($model->getRecent() as $m)
					{
						echo '<li>'.Html::a($m->title,["//yes/product/view","id"=>$m->id,"title"=>$m->title]).'</li>';
					}				
				?>		
			</ul>
			<hr>
			<h4><?= Yii::t("app","Categories")?></h4>
			<ul class="nav nav-pills nav-stacked">
				<?php
					foreach ($cat->parents() as $c)
					{
						echo '<li>'.Html::a($c->title,["//yes/product/index","category"=>$c->title]).'</li>';
					}				
				?>						
			</ul>
			<hr>
			<h4><?= Yii::t("app","Archive")?></h4>
			<ul class="nav nav-pills">
				<?php
					foreach ($model->getArchived() as $m)
					{
						echo '<li>'.Html::a(date('M Y',strtotime($m["month"])),["//yes/product/index","time"=>$m["month"]]).'</li>';
					}				
				?>				
			</ul>
			<hr>
			<div class="shopcart-box">
				<h4><?= Yii::t("app","Shopping Cart")?> <span class="shopcart-badge badge"></span> <small class="pull-right"></small></h4>
				<table class="table table-striped table-bordered">
				</table>
				<?= Html::a(Yii::t("app","Checkout"),["//yes/order/create"],["class"=>"btn btn-success pull-right"])?>
			</div>
		</div>
		<!-- End Sidebar -->
	</div>

</div>

<?= Colorbox::widget([
    'targets' => [
        '.colorbox' => [
            'maxWidth' => 800,
            'maxHeight' => 600,
            'rel'=>'colorbox',
            'slideshow'=>true
        ],
    ],
    'coreStyle' => 1
]) ?>

<?php

$this->render('@amilna/yes/views/product/_script_add',['model'=>$model]);


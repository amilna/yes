<?php

use yii\helpers\Html;
use yii\helpers\Url;
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

$cat = new Category();
$module = Yii::$app->getModule("yes");
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>    

	<div class="row">
		<!-- Product -->
		<div class="col-sm-8">
			<div>
				<div>
					<h3><?= $model->author->username ?> <small><i class="glyphicon glyphicon-time"></i>  <?= date('D d M, Y',strtotime($model->time)) ?> </small></h3>
				</div>				
				<div>
					<div class="row">	
						<div class="col-sm-6">
							
					<?php
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
								'baseUrl'=>Yii::$app->urlManager->baseUrl.'/upload',
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
						
						<?= $model->description ?>
						<div class="well col-sm-6 pull-right" style="margin-top:20px">
														
							<h3><?php 
								$price = ($model->discount > 0?$model->price*$model->discount/100:$model->price);
								echo $module->currency["symbol"].number_format($price,2,$module->currency["decimal_separator"],$module->currency["thousand_separator"]); 
							?></h3>
							<hr>
							<?php
							echo Html::hiddenInput('Orders[][product_id]',$model->id,[]);
							echo Html::hiddenInput('Orders[][product_price]',$price,[]);
							
							echo '<div class="form-group"><label class="control-label">'.Yii::t("app","Quantity").'</label>';
							echo TouchSpin::widget([
										'name' => 'Orders[][product_qty]',
										'value' => 0,
										'options' => ['class'=>'item-chart'],
										'pluginOptions'=>[
											'min'=>0,												
											'step'=>1,
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
									
									echo '<div class="form-group"><label class="control-label">'.$d->label.'</label>';
									echo Select2::widget([
										'name' => 'Orders[]['.$d->label.']', 
										'data' => $options,
										'value' => $deval,
										'options' => [
											'placeholder' => Yii::t('app','Select ').$d->label,
											'class'=>'item-chart'
										],
									]);	
									echo '</div>';
								}
								else if ($type == 1)
								{
									echo '<div class="form-group"><label class="control-label">'.$d->label.'</label>';
									echo Html::textInput('Orders[]['.$d->label.']',$d->value,["class"=>"form-control item-chart","placeholder"=>Yii::t("app",$d->label),"style"=>"width:100%"]);
									echo '</div>';	
								}
								else if ($type == 2)
								{
									echo '<div class="form-group"><label class="control-label">'.$d->label.'</label>';
									echo TouchSpin::widget([
											'name' => 'Orders[]['.$d->label.']', 										
											'value' => ($d->value == null?0:$d->value),
											'options'=>["class"=>"item-chart"],
											'pluginOptions'=>[
												'min'=>0,												
												'step'=>1,
												'handle'=>'triangle',
												'tooltip'=>'always'
											]
										]);									
									echo '</div>';	
								}
								else if ($type == 3)
								{
									echo '<div class="form-group"><label class="control-label">'.$d->label.'</label>';
									echo Html::textArea('Orders[]['.$d->label.']',$d->value,["class"=>"form-control item-chart","placeholder"=>Yii::t("app",$d->label),"style"=>"width:100%"]);
									echo '</div>';
								}
								else if ($type == 4)
								{
									echo '<div class="form-group"><label class="control-label">'.$d->value.'</label>';
								}							
								else if ($type == 5)
								{
									echo Html::hiddenInput('Orders[]['.$d->label.']',$d->value,["class"=>"form-control item-chart"]);
								}
							}							
							?>
							<a class="btn btn-primary"><?= Yii::t("app","Add to Chart")?></a>
						</div>
													
						<?= $model->content ?>
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
			<h4>Search our Products</h4>
			
			<form action="index" method="get">
				<div class="input-group">
					<input class="form-control input-md" name="ProductSearch[search]" id="appendedInputButtons" type="text">
					<span class="input-group-btn">
						<button class="btn btn-md" type="button">Search</button>
					</span>
				</div>
			</form>
			
			<h4>Recent Products</h4>
			<ul>
				<?php
					foreach ($model->getRecent() as $m)
					{
						echo '<li>'.Html::a($m->title,["//yes/product/view?id=".$m->id]).'</li>';
					}				
				?>		
			</ul>
			<h4>Categories</h4>
			<ul>
				<?php
					foreach ($cat->parents() as $c)
					{
						echo '<li>'.Html::a($c->title,["//yes/product/index?ProductSearch[category]=".$c->title]).'</li>';
					}				
				?>						
			</ul>
			<h4>Archive</h4>
			<ul>
				<?php
					foreach ($model->getArchived() as $m)
					{
						echo '<li>'.Html::a(date('M Y',strtotime($m["month"])),["//yes/product/index?ProductSearch[time]=".$m["month"]]).'</li>';
					}				
				?>				
			</ul>
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

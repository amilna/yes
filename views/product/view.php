<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use amilna\yes\models\Category;
use himiklab\colorbox\Colorbox;
use amilna\elevatezoom\ElevateZoom;

/* @var $this yii\web\View */
/* @var $model amilna\yes\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$cat = new Category();
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
							]);
						}
						
						
					?>					
						<br>	
						</div>
						<div class="col-sm-6">
							<?= $model->description ?>
						</div>	
					</div>
				</div>
				<div>
					<?= $model->content ?>
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

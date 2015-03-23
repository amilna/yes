<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model amilna\cap\models\AccountCode */
$module = Yii::$app->getModule('yes');

$this->params['cboxTarget']['.colorbox-'.$model->id] =  [
													'maxWidth' => 800,
													'maxHeight' => 600,
													'rel'=>'colorbox-'.$model->id,
													'slideshow'=>true
												];

?>

	<div class="thumbnail">	
		<?php
			if ($model->images != null)
			{
				$images = json_decode($model->images);
				$n = 0;
				foreach ($images as $i) {					
					echo Html::a(Html::tag("div","",["style"=>'height:100px;background-position: 0% 30%;background-size:cover;background-image:url("'.str_replace($module->uploadURL."/",$module->uploadURL."/.thumbs/",$i).'")']),$i,["titles"=>$model->title,"class"=>"colorbox-".$model->id,"style"=>"width:100%;".($n > 0?"display:none;":"")]);
					$n += 1;
				}
			}
		?>													
		<div class="caption">
			<h4><?= Html::a($model->title,["//yes/product/view?id=".$model->id]) ?></h4>
			<h5><?= Html::encode($model->author->username) ?> <small><?= date('D d M, Y',strtotime($model->time)) ?></small></h5>								
		
			<p><?= Html::encode($model->description) ?></p>
			<p>
			<?= Html::a(Yii::t('app','Read More'),["//yes/product/view?id=".$model->id],['class'=>'btn btn-small btn-default']) ?>			
			</p>		
		</div>
	</div>


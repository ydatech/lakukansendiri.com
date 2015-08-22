
<?php
	use yii\helpers\Html;
	$homeurl = Yii::$app->homeUrl;
	//print_r($data);
?>

<?php foreach($model as $step):?>

<h3> <?= Html::encode($step->step_title);?></h3>
<hr>
<?php if($step->step_picture):?>
<img class="img-responsive" src="<?= $homeurl . 'image/step/' . $step->step_picture ;?>" alt="<?= $step->step_title?>">
<hr>
<?php endif;?>
<?php if($step->step_video):?>
<div class="embed-responsive embed-responsive-16by9">
	<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $step->step_video?>"></iframe>
</div>
<hr>
<?php endif;?>
<p>
	<?= $step->step_description?>
</p>
<?php endforeach;?>





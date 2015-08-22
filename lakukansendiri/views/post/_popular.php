<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
	?>
	
	<?php foreach($data as $popular):?>
	<li><?= Html::a($popular['title'],Url::to(['view','id'=>$popular['id'],'slug'=>$popular['slug']]))?></li>
	
	<?php endforeach;?>
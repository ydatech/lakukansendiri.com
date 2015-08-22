
<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	$homeurl = Yii::$app->homeUrl;
?>
<!-- Projects Row -->
<div class="row">
	<?php if(!$model == null):?>
	<?php foreach($model as $post):?>
	
	<div class="col-sm-4 col-lg-4 col-md-4">
		<div class="thumbnail">
		<a href="<?= Url::to(['post/view','id'=>$post->id,'slug'=>$post->slug])?>">
			<img src="<?php echo $post->post_cover?$homeurl.'image/instruksi/thumb_' . $post->post_cover:$homeurl.'image/350x140.jpg';?>" alt="<?= $post->title?>">
			</a>
			<div class="caption ">
				
				<h4><?php echo Html::a($post->title,Url::to(['post/view','id'=>$post->id,'slug'=>$post->slug]));?>
				</h4>
				<small>Oleh <?= Html::a($post->user->displayName,Url::to(['app/author','u'=>$post->user->username]))?></small>
				<p class="text-justify"><?php
					
					if(strlen($post->description) > 140){
				
					echo substr($post->description,0,140).'...';
					}else{
					echo $post->description;
					}
					?></p>
			</div>
			<div class="ratings">
				<p><span class="fa fa-eye"></span> <?= $post->posthitscount?> Melihat | <span class="fa fa-star"></span> <?= $post->postfavorit?> Favorit 
				 | <span class="fa fa-gavel"></span> <?= $post->postmade?> Membuat</p>
				
				
			</div>
		</div>
	</div>
	<!--
	<div class="col-md-4 portfolio-item">
		<a href="#">
			<img class="img-responsive" src="<?php echo $post->post_cover?$homeurl.'image/instruksi/thumb_' . $post->post_cover:'http://placehold.it/700x400';?>" alt="">
		</a>
		<h3>
			<a href="#"><?php echo $post->title;?></a>
		</h3>
		<p><?php echo $post->description;?></p>
	</div>-->
	<?php endforeach;?>
	<?php else:?>
	<div class="col-lg-12">
	<p class="lead" > Belum ada instruksi tentang <?= $keyword ?>, jika kamu pernah membuatnya ayo bagikan kreatifitasmu disini dengan : <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Buat Instruksi Baru</button></p>
	</div>
	<?php endif;?>
</div>
<!-- /.row -->
<?php //echo $data->totalCount;
	$new_total_groups = ceil($pages->totalCount / $pages->pageSize);
	
?>
<script type="text/javascript">
	new_total_groups = '<?php echo $new_total_groups; ?>';
    total_posts = '<?php echo $pages->totalCount; ?>';
	
</script>


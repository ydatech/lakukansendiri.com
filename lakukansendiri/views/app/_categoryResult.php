
<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	$homeurl = Yii::$app->homeUrl;
	
?>
<!-- Projects Row -->
<div class="row">
	<?php if(!$provider->getModels() == null):?>
	<?php foreach($provider->getModels() as $post):?>
	
	<div class="col-sm-4 col-lg-4 col-md-4">
		<div class="thumbnail">
		<a href="<?= Url::to(['post/view','id'=>$post['id'],'slug'=>$post['slug']])?>">
			<img src="<?php echo $post['cover']?$homeurl.'image/instruksi/thumb_' . $post['cover']:$homeurl.'image/350x140.jpg';?>" alt="<?= $post['title']?>">
			</a>
			<div class="caption ">
				
				<h4><?php echo Html::a($post['title'],Url::to(['post/view','id'=>$post['id'],'slug'=>$post['slug']]));?>
				</h4>
				<small>Oleh <?= Html::a($post['displayName'],Url::to(['app/author','u'=>$post['username']]))?></small>
				<p class="text-justify"><?php
					
					if(strlen($post['description']) > 140){
				
					echo substr($post['description'],0,140).'...';
					}else{
					echo $post['description'];
					}
					?></p>
			</div>
			<div class="ratings">
				<p><span class="fa fa-eye"></span> <?= $this->context->getPostHits($post['id'])?> Melihat | <span class="fa fa-star"></span> <?= $this->context->getPostInfo($post['id'],'favorit')?> Favorit 
				 | <span class="fa fa-gavel"></span> <?= $this->context->getPostInfo($post['id'],'made')?> Membuat</p>
				
				
			</div>
		</div>
	</div>

	<?php endforeach;?>
	<?php else:?>
	<div class="col-lg-12">
	<p class="lead" > Tidak ditemukan instruksi dengan kategori <strong> <?= ($sub == null)?$this->context->getCategoryName($cat):$this->context->getSubcategoryName($sub) ?> </strong>, ayo bantu orang-orang dengan <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Buat Instruksi Baru</button></p>
	</div>
	<?php endif;?>
</div>
<!-- /.row -->
<?php //echo $data->totalCount;
	$new_total_groups = ceil($provider->totalCount / $provider->pagination->pageSize);
	
?>
<script type="text/javascript">
	new_total_groups = '<?php echo $new_total_groups; ?>';
    total_posts = '<?php echo $provider->totalCount; ?>';
	
</script>


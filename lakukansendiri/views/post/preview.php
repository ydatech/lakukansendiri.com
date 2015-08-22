<?php
	
	use yii\helpers\Html;
	use yii\widgets\DetailView;
	use yii\helpers\Url;
	use yii\widgets\LinkPager;
	use app\models\PostInfo;
	use yii\authclient\widgets\AuthChoice;
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	
	$this->title = 'Langkah Ke-' . (string)($pages->page+1) .' '.$model->title;
	/*
		$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
		$this->params['breadcrumbs'][] = $this->title;
	*/	
	
	$homeurl = Yii::$app->homeUrl;
?>

<div class="row">
	
	<!-- Blog Post Content Column -->
	<div class="col-lg-8">
		
		<!-- Blog Post -->
		
		<!-- Title -->
		<h1><?php echo 'Langkah Ke-' . (string)($pages->page+1) .' '. $model->title;?></h1>
		
		<!-- Author -->
		<p class="lead">
			oleh <a href="#"><?php echo $model->user->displayName;?></a>
		</p>
		
		<hr>
		
		<!-- Date/Time -->
		<p><i class="fa fa-clock-o"></i> Dibuat pada <?php echo $model->indonesiancalender; ?></p>
		
		
		<hr>
		<?php if($model->post_cover):?>
		<!-- Preview Image -->
		<img class="img-responsive" src="<?php echo $homeurl . 'image/instruksi/' . $model->post_cover ;?>" alt="">
		
		<hr>
		<?php endif;?>
		
		<!-- Post Content -->
		<p class="lead"><?php echo $model->description;?></p>
		<hr>
		
		<?= $this->render('_viewMaterial', [
			'model' => $modelMaterial,
		]) ?>
		<?php echo LinkPager::widget([
			'pagination' => $pages,
			'firstPageLabel'=>'Langkah ke-'
		]);?>
		<hr>
		<h2> Langkah Ke-<?= $pages->page+1;?></h2>
		<?= $this->render('_viewStep', [
			'model' => $modelStep,
		]) ?>
		<hr>
		<?php echo LinkPager::widget([
			'pagination' => $pages,
			'firstPageLabel'=>'Langkah ke-'
		]);?>
		<hr>
		
		<h3>Lihat Juga : </h3>
		<hr>
		<ul>
			
			<?php foreach($related as $relatedpost):?>
			<?php if($relatedpost->id !== $model->id):?>
			<li> <h4><?= Html::a($relatedpost->title,Url::to(['view','id'=>$relatedpost->id,'slug'=>$relatedpost->slug]))?></h4></li>
			<?php endif;?>
			<?php endforeach;?>
		</ul>
		<hr>
		<!-- Blog Comments -->
		
		<!-- Comments Form -->
		<div class="well comment-form-container">
			<?php if(Yii::$app->user->isGuest):?>
			<div class="text-center">
				<small> Silahkan Login/Register Untuk Berkomentar </small>
				<?php $authAuthChoice = AuthChoice::begin([
					'baseAuthUrl' => ['app/auth'],
					'popupMode' => true,
				]); ?>
				<?php foreach ($authAuthChoice->getClients() as $client): ?>
				<?php switch($client->getId()):
				case 'facebook':?>
				<?php $authAuthChoice->clientLink($client,'<i class="fa fa-facebook"></i> ' . ucfirst($client->getId()),['class'=>'btn btn-info']) ?>
				<?php break;?>
				<?php case 'google':?>
				<?php $authAuthChoice->clientLink($client,'<i class="fa fa-google-plus"></i> '.ucfirst($client->getId()),['class'=>'btn btn-danger']) ?>
				
				<?php break;?>
				<?php endswitch;?>
				<?php endforeach; ?>
				
				<?php AuthChoice::end(); ?>
			</div>
			<?php endif;?>
		</div>
		
		<hr>
		
		<!-- Posted Comments -->
		
		<!-- Comment -->
		<div class="comment-content">
		</div>
		
		
		
	</div>
	
	<!-- Blog Sidebar Widgets Column -->
	<div class="col-md-4">
		
		<!-- Blog Search Well -->
		<div class="well text-center">
			<div class="row">
				<div class="col-lg-6">
					<h4>Instruksi Info</h4>
					<p > <i class="fa fa-eye"></i> <?= $model->posthitscount ?> Melihat</p>
					<p id="post-favorit" data-favoritcount="<?= $model->postfavorit ?>"> <i class="fa fa-star"></i> <?= $model->postfavorit ?> Favorit</p>
					<p id="post-made" data-madecount="<?= $model->postmade ?>"> <i class="fa fa-gavel"></i> <?= $model->postmade ?> Membuat</p>
				</div>
				<div class="col-lg-6">
					<h4> Author Info </h4>
					
					<img style="width:50px" src="<?= $model->user->avatar?>" alt="<?= $model->user->displayName?>" class="img-circle">
					<p><a href="<?= Url::to(['app/user','u'=>$model->user->username])?>"> <?= $model->user->displayName?></a> </p>
					<small> Bergabung sejak: </small>
					<p><?= $model->user->indonesiancalender?></p>
				</div>
			</div>
			
		</div>
		
		<!-- Blog Categories Well -->
		<div class="well text-center">
			<h4></h4>
			<div class="row ">
				<button id="favorited-btn" class="btn btn-md btn-primary favorited-btn" style="display:none;"><i class="fa fa-star"></i> Di Favoritkan</button>
				<div class="col-lg-12">
					
					<?php if(!Yii::$app->user->isGuest):?>
					<?php if(PostInfo::find()->where( [ 'post_id' => $model->id,'user_id'=>Yii::$app->user->id,'type'=>PostInfo::FAVORIT] )->exists()):?>
					<button id="favorit-btn" data-favorit="true" class="btn btn-md btn-primary favorit-btn"><i class="fa fa-star"></i> Batal Favorit</button>
					
					<?php else:?>
					<button id="favorit-btn" data-favorit="false" class="btn btn-md btn-primary favorit-btn"><i class="fa fa-star"></i> Favorit</button>
					
					<?php endif;?>
					<?php if(PostInfo::find()->where( [ 'post_id' => $model->id,'user_id'=>Yii::$app->user->id,'type'=>PostInfo::MADE] )->exists()):?>
					<button id="made-btn" data-made="true" class="btn btn-md btn-info made-btn"><i class="fa fa-gavel"></i> Batal Membuat</button>
					<?php else:?>
					<button id="made-btn" data-made="false" class="btn btn-md btn-info made-btn"><i class="fa fa-gavel"></i> Membuat</button>
					<?php endif;?>
					<?php else:?>
					<button id="favorit-btn-unlogin" class="btn btn-md btn-primary" data-toggle="modal" data-target="#create-modal"><i class="fa fa-star"></i> Favorit</button>
					<button id="made-btn-unlogin" class="btn btn-md btn-info" data-toggle="modal" data-target="#create-modal"><i class="fa fa-gavel"></i> Membuat</button>
					
					<?php endif;?>
				</div>
				
			</div>
			<!-- /.row -->
		</div>
		
		<!-- Side Widget Well -->
		<div class="well">
			<h4>Instruksi Populer</h4>
			
			<ul class="instruksi-popular">
				<?= $this->render('_popular',['data'=>$popular])?>
			</ul>
			
			
		</div>
		
	</div>
	
</div>
<!-- /.row -->

<hr>
<div class="preview-overlay" >
	<i class="fa fa-eye fa-2x white" ></i>
	<span><h1>Preview Instruksi</h1></span>
</div>
<?php
	
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/view.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    //'media' => 'print',
	], 'css-landingpage');
	$this->registerJs("
	$.get('{$homeurl}comment/get',{postid:{$model->id}},function(respone){
	$('.comment-content').html(respone);
	});
	var track_load = 2; 
	var loading  = false;
	
	
	$(document).ajaxStop(function(){
	
	$('.more-comment-btn').click(function(){
	//alert(\"hello\");
	var btn = $(this);
	if(track_load <= new_total_groups && loading==false) //there's more data to load
	{
	loading = true; //prevent further ajax loading
	$(btn).html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Sedang Memuat');
	
	$.get('{$homeurl}comment/get',{'postid':'{$model->id}','page':track_load},function(respone){
	$('.more-comment-btn-container').replaceWith(respone);
	track_load++;
	loading = false;
	$(btn).html('<i class=\"fa fa-arrow-down\"></i> Muat Lebih Banyak');
	
	}).fail(function(xhr, ajaxOptions, thrownError) {
	
	loading = false;
	$(btn).html('<i class=\"fa fa-arrow-down\"></i> Muat Lebih Banyak');
	});
	}
	else{
	
	btn.html('Komentar Sudah Dimuat Semua');
	}
	});
	
	});
	
	
	");
	
	if(!Yii::$app->user->isGuest){
		$this->registerJs("
		$.get('{$homeurl}comment',{postid:{$model->id}},function(respone){
		$('.comment-form-container').html(respone);
		});
		$('.favorit-btn').click(function(){
		var favorit = $(this).attr('data-favorit');
		var btn =$(this);
		if(favorit == 'false'){
		btn.html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Favorit');
		$.post('{$homeurl}postinfo/favorit',{postid:'{$model->id}'},function(respone){
		if (respone.status == 'favorited'){
		
		btn.attr('data-favorit','true');
		btn.html('<i class=\"fa fa-star\"></i> Batal Favorit');
		var favoritcount = parseInt($('#post-favorit').attr('data-favoritcount'));
		favoritcount++;
		$('#post-favorit').html('<i class=\"fa fa-star\"></i> '+favoritcount+' Favorit' );
		$('#post-favorit').attr('data-favoritcount',favoritcount);
		
		}
		},'json');
		
		}
		else if(favorit == 'true'){
		btn.html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Batal Favorit');
		$.post('{$homeurl}postinfo/unfavorit',{postid:'{$model->id}'},function(respone1){
		if(respone1.status == 'unfavorited'){
		btn.attr('data-favorit','false');
		btn.html('<i class=\"fa fa-star\"></i> Favorit');
		var favoritcount = parseInt($('#post-favorit').attr('data-favoritcount'));
		favoritcount--;
		$('#post-favorit').html('<i class=\"fa fa-star\"></i> '+favoritcount+' Favorit' );
		$('#post-favorit').attr('data-favoritcount',favoritcount);
		
		}
		},'json');
		}
		
		});
		
		$('.made-btn').click(function(){
		var made = $(this).attr('data-made');
		var btn =$(this);
		if(made == 'false'){
		btn.html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Membuat');
		$.post('{$homeurl}postinfo/made',{postid:'{$model->id}'},function(respone){
		if (respone.status == 'made'){
		
		btn.attr('data-made','true');
		btn.html('<i class=\"fa fa-gavel\"></i> Batal Membuat');
		
		var madecount = parseInt($('#post-made').attr('data-madecount'));
		madecount++;
		$('#post-made').html('<i class=\"fa fa-gavel\"></i> '+madecount+' Membuat' );
		$('#post-made').attr('data-madecount',madecount);
		}
		},'json');
		
		}
		else if(made == 'true'){
		btn.html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Batal Membuat');
		$.post('{$homeurl}postinfo/unmade',{postid:'{$model->id}'},function(respone1){
		if(respone1.status == 'unmade'){
		btn.attr('data-made','false');
		btn.html('<i class=\"fa fa-gavel\"></i> Membuat');
		var madecount = parseInt($('#post-made').attr('data-madecount'));
		madecount--;
		$('#post-made').html('<i class=\"fa fa-gavel\"></i> '+madecount+' Membuat' );
		$('#post-made').attr('data-madecount',madecount);
		
		}
		},'json');
		}
		
		});
		
		
		
		
		
		
		
		");
	}
?>
',<?php 
	
	use yii\helpers\Url;
	use yii\helpers\Html;
	
	$this->title = "User Profile | ".$user->displayName;
	$homeurl = Yii::$app->homeUrl;
?>
<h1> <?= $this->title?>
</h1>
<hr>

<div class="col-sm-3">
	<!--left col-->
	<ul class="list-group">
		
		<li class="list-group-item text-muted" contenteditable="false">Profile <i class="fa fa-user"></i></li>
		<li class="list-group-item"><img style="margin: 0 auto;" title="profile image" class="img-responsive" src="<?= $user->avatar?>"></li>
		
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Bergabung</strong></span> <?= $user->indonesiancalender?></li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Username</strong></span>
			
			
			<?= $user->username?>
		</li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Display Name </strong></span>
			<?= $user->displayName?>
			
			</li>
		
		
	</ul>
	<ul class="list-group">
		<li class="list-group-item text-muted">Aktifitas <i class="fa fa-dashboard fa-1x"></i>
			
		</li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Publik Instruksi</strong></span> <?= count($user->post)?></li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Favorit</strong></span> <?= count( $user->favorit)?></li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Membuat</strong></span> <?= count($user->made)?></li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Komentar</strong></span> <?= count($user->comment)?></li>
	</ul>
</div>
<!--/col-3-->
<div class="col-sm-9" contenteditable="false" style="">
	
	<div class="panel panel-default target">
		<div class="panel-heading" contenteditable="false">Instruksi Terbaru</div>
		<div class="panel-body">
			<div class="row">
				<?php foreach($user->latestPost as $latest):?>
				<div class="col-md-4">
					<div class="thumbnail">
						<img alt="<?= $latest->title?>" src="<?= $latest->post_cover?$homeurl.'image/instruksi/thumb_' . $latest->post_cover:$homeurl.'image/350x140.jpg';?>">
						<div class="caption">
							<h4>
								<?= Html::a($latest->title, Url::to(['post/view','id'=>$latest->id,'slug'=>$latest->slug]))?>
							</h4>
						</div>
					</div>
				</div>
				<?php endforeach;?>
				
			</div>
			<?php if(count($user->post) == 0):?>
			<p class="lead" > Tidak ditemukan instruksi oleh user terkait, ayo jadilah yang pertama untuk berbagi kreatifitas dengan <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Buat Instruksi Baru</button></p>
			
			<?php endif;?>
			<?php if(count($user->post) > 3):?>
			<a id="more-instruksi" href="<?= Url::to(['app/author','u'=>$user->username])?>"> Lebih banyak instruksi... </a>
			<?php endif;?>
		</div>
		
	</div>
</div>


<?php 
	
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/homepage.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
	//'media' => 'print',
	], 'css-landingpage');
?>
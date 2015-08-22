<?php
	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use app\assets\AppAsset;
	use yii\bootstrap\Modal;
	use yii\helpers\Url;
	use yii\authclient\widgets\AuthChoice;
	/* @var $this \yii\web\View */
	/* @var $content string */
	
	AppAsset::register($this);
	$homeurl = Yii::$app->homeUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
		
	</head>
	<body>
		
		<?php $this->beginBody() ?>
		<div class="wrap">
			<?php
				NavBar::begin([
                'brandLabel' => 'LakukanSendiri.com <span class="label label-danger">Beta</span>',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
				'class' => 'navbar-default yamm navbar-fixed-top',
                ],
				]);
				echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
				//['label' => 'Home', 'url' => ['/app/index']],
				// ['label' => 'About', 'url' => ['/app/about']],
				//['label' => 'Contact', 'url' => ['/app/contact']],
				'<li><button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Buat Instruksi</button></li>',
				Yii::$app->user->isGuest ? 
				'<li> <a href="javascript:void(0)" id="login-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-sign-in"></i> Login/Register</a></li>':
				'
				<li><a href="'.Url::to(['post/index']).'"> <i class="fa fa-list-ul"></i> Instruksi Saya</a></li>
				<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <img width="25px" alt="'. Yii::$app->user->identity->displayName .'" class="img-circle" src="'.Yii::$app->user->identity->avatar.'" ></img> '. Yii::$app->user->identity->displayName .'<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
			<li><a href="'.Url::to(['app/setting']).'"> <i class="fa fa-cog"></i> Pengaturan Profil</a></li>
           <li class="divider"></li>
            <li><a data-method="post" href="'.Url::to(['app/logout']).'"><i class="fa fa-sign-out" ></i>Logout</a></li>
          </ul>
        </li>',
				
				],
				]
				);
				
				
				
				echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
				//['label' => 'Home', 'url' => ['/app/index']],
				// ['label' => 'About', 'url' => ['/app/about']],
				//['label' => 'Contact', 'url' => ['/app/contact']],
				'<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-ul"></i> Kategori</a>
				<ul class="dropdown-menu">
				<li>
				<div class="yamm-content">
				<div class="row category-list">
				</div>
				</div>
				</li>
				</ul>
				</li>',
				],
				]
				);
				echo '
				<form id="nav-search-form" action="'.$homeurl.'search" class="navbar-form navbar-left" role="search">
				<div class="input-group">
				
				<label class="sr-only" for="keywordnav">Pencarian</label>
				<input type="text" class="form-control" name="k" id="keywordnav" placeholder="Pencarian" required></input>
				<div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
				</div>
				
				
				</div>
				
				</form>';
				NavBar::end();
			?>
			
			<div class="container">
				<?= Breadcrumbs::widget([
					'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					'homeLink'=>['label'=>'Beranda','url'=>$homeurl,'template'=>'<li><i class="fa fa-home"></i> {link}</li>']
				]) ?>
				<?= $content ?>
			</div>
		</div>
		<div class="latest-spinner text-center" style="display:none;" >
			<i class="ajax-loading-icon fa fa-spin fa-cog fa-2x"  ></i>
			
		</div>
		<footer class="footer">
			<div class="container">
				<p class="pull-left">&copy; LakukanSendiri.com <?= date('Y') ?></p>
				<p class="pull-right"></p>
			</div>
		</footer>
		
		<?php
			
			
			
		?>
		
		
		<?php Modal::begin([
			'header' => Yii::$app->user->isGuest?'<h2> Login/Register </h2>':'<h2>Buat Instruksi Baru</h2>',
			'id'=>'create-modal',
			//'toggleButton' => ['label' => 'click me'],
		]);?>
		<?php if(!Yii::$app->user->isGuest):?>
		<div class="post-form">
			
		</div>
		<?php else:?>
	
		<div class="text-center">
		
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
		<?php Modal::end();?>
		
		
		<?php 
			$url =  Yii::$app->homeUrl;
			if(!Yii::$app->user->isGuest){
				$this->registerJs("
				
				$('.post-form').load('{$url}post/create');
				
				");
			}
			$this->registerJs("
			$(document).ajaxStop(function(){
			
			$('.loading-overlay').fadeOut(1000);
			
			
			});
			
			$('.category-list').load('{$url}app/getcategory');
			");
			$this->registerCss("
			.preview-overlay, .loading-overlay{
			background-color:  rgba(0,0,0,0.6);
			width:100%;
			height:100%;
			position:fixed;
			top:0;
			left:0;
			z-index:9999;
			}
			.preview-overlay i, .loading-overlay i{
			display:block;
			margin:20% auto 15px;
			width:100px;
			height:auto;
			}
			
			.preview-overlay, .loading-overlay{
			display:block;
			text-align:center;
			color:#fff;
			//font-family:'latobold';
			font-size:15px;
			
			
			}
			");
		?>
		
		
		<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>

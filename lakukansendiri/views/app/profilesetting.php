<?php 
	
	use yii\widgets\ActiveForm;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use dosamigos\editable\Editable;
	use yii\web\JsExpression;
	
	$this->title = "Profile Setting";
	$homeurl = Yii::$app->homeUrl;
?>
<h1> <?= $this->title ?>
</h1>
<hr>
<?php $form = ActiveForm::begin([
	'id'=>'edit-avatar-picture',
	'action' => ['editavatar'],
	'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($modelUploadFoto, 'avatar',['options'=>['style'=>'display:none;'],'errorOptions'=>['class'=>'help-block','id'=>'edit-avatar-picture-error']])->fileInput() ?>
<?php ActiveForm::end(); ?>
<div class="col-sm-3">
	<!--left col-->
	<ul class="list-group">
		
		<li class="list-group-item text-muted" contenteditable="false">Profile <i class="fa fa-user"></i></li>
		<li class="list-group-item">
			<i style="display:none;" class="ajax-loading-icon fa fa-spin fa-cog" id="edit-avatar-picture-spinner" ></i>
	
			<img style="margin: 0 auto;" data-id="<?= $user->id?>" id="user-avatar" title="klik untuk mengedit" class="img-responsive" src="<?= $user->avatar?>">
			<small id="edit-avatar-error" class="text-center text-danger" ></small></li>
		
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Bergabung</strong></span> <?= $user->indonesiancalender?></li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Username</strong></span>
			
			
			<?= Editable::widget( [
				'model' => $user,
				
				'attribute' => 'username',
				'url' => 'app/inlineupdateuser',
				'type' => 'text',
				//'type'=>'select',
				'mode'=>'popup',
				'options'=>[
				],
				'clientOptions' => [
				'emptytext'=>'Edit',
				'showbuttons'=>false,
				'onblur'=>'submit',
				'pk' => $user->id,
				//'value'=>$data->order,
				//'source' =>$order,
				'success'=>new JsExpression("
				function(response, newValue){
				var homeurl = '{$homeurl}';
				$('#more-instruksi').attr('href',homeurl+'author/'+newValue);
				}"),
				
				//'placement' => 'right',
				
				]
			]);?>
		</li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Display Name </strong></span>
						<?= Editable::widget( [
				'model' => $user,
				
				'attribute' => 'displayName',
				'url' => 'app/inlineupdateuser',
				'type' => 'text',
				//'type'=>'select',
				'mode'=>'popup',
				'options'=>[
				],
				'clientOptions' => [
				'emptytext'=>'Edit',
				'showbuttons'=>false,
				'onblur'=>'submit',
				'pk' => $user->id,
				//'value'=>$data->order,
				//'source' =>$order,

				
				//'placement' => 'right',
				
				]
			]);?>
			
			</li>
		<li class="list-group-item text-right"><span class="pull-left"><strong class="">Email </strong></span>
				<?= Editable::widget( [
				'model' => $user,
				
				'attribute' => 'email',
				'url' => 'app/inlineupdateuser',
				'type' => 'text',
				//'type'=>'select',
				'mode'=>'popup',
				'options'=>[
				],
				'clientOptions' => [
				'emptytext'=>'Edit',
				'showbuttons'=>false,
				'onblur'=>'submit',
				'pk' => $user->id,
				//'value'=>$data->order,
				//'source' =>$order,

				
				//'placement' => 'right',
				
				]
			]);?>
			
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
			<p class="lead" > Tidak ditemukan instruksi, ayo bagikan kreatifitasmu dengan <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Buat Instruksi Baru</button></p>
			
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
	
	$this->registerJs("
	var userid = '';
	$('#user-avatar').click(function(){
	$('#uploadfoto-avatar').click();
	userid = $(this).attr('data-id');
	});
	
	$('#uploadfoto-avatar').change(function(){
	$('form#edit-avatar-picture').submit().on('afterValidate',function(e, msg, parentEvent){
	var errmessage = $('div#edit-avatar-picture-error').text();
	if(errmessage.length){
	$('#edit-avatar-error').text(errmessage);
	}
	
	
	}).on('beforeSubmit', function(event, jqXHR, settings) {
	
	
	
	var form = $(this);
	
	if(form.find('.has-error').length) {	
	return false;
	}
	var formUpload = document.getElementById('edit-avatar-picture');
	$('#edit-avatar-picture-spinner').show();
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
	processData: false,
	contentType: false,
	data: new FormData(formUpload),//form.serialize(),
	dataType:'json',
	cache:false,
	success: function(data) {
	
	if(data.status == 'saved'){
	//window.location.href = data.url;
	
	$.post('{$homeurl}app/updateavatar',{userid:userid,newpic:data.url},function(respone){
	if(respone.status == 'saved'){
	$('#user-avatar').attr('src',data.url);
	}
	},'json');
	
	//$('#step-step_picture').attr('value',data.filename).trigger('change');
	
	$('#edit-avatar-picture-spinner').hide();
	}
	},
	error: function () {
	$('#edit-avatar-picture-spinner').hide();
	}
	});
	
	return false;
	});
	
	});
	
	");
?>
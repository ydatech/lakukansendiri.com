<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	
	//use dosamigos\ckeditor\CKEditor;
	
	use yii\helpers\Url;
	
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	/* @var $form yii\widgets\ActiveForm */
	$homeurl = Yii::$app->homeUrl;
?>

<div class="post-step-form">
	
    <?php $form = ActiveForm::begin([
		'id'=>'step-form',
		'enableAjaxValidation' => true,
		'validationUrl'=>['validatecreatestep'],
		'action' => ['createstep']
	]); ?>
	 <?= $form->field($model, 'order')->textInput()->label('Langkah Ke') ?>
    <?= $form->field($model, 'step_title')->textInput(['maxlength' => 255])->label('Judul Untuk Langkah Ini') ?>
	<?= $form->field($model, 'post_id')->hiddenInput(['value'=>$postid])->label(false)?>
	<?= $form->field($model, 'step_picture')->hiddenInput()->label(false)?>
	<?= $form->field($model,'step_description')->textarea(['rows'=>6])->label('Dekripsikan Langkah Ini')?>
	<?= $form->field($model, 'step_video')->textInput(['maxlength' => 255])->label('URL Youtube Video Untuk Langkah Ini (Optional)') ?>
	
	
	
	
    <?php ActiveForm::end(); ?>
	<?php $form = ActiveForm::begin([
		'id'=>'step-picture-form',
		
		'action' => ['uploadfotostep'],
		'options' => ['enctype' => 'multipart/form-data']
	]); ?>
	<?= $form->field($foto, 'imagefile')->fileInput()->label('Foto Untuk Langkah Ini') ?>
	<?php ActiveForm::end(); ?>
	
	<div class="form-group field-post-cover-preview">
		<i style="display:none;" class="ajax-loading-icon fa fa-spin fa-cog" id="step-picture-upload-spinner" ></i>
		<img id="step-picture-preview" class="img-responsive" src="<?php echo $homeurl.'image/350x140.jpg'; ?>" alt="">
		
	</div>
	<div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambahkan Langkah Ini', ['id'=>'step-btn','form'=>'step-form','class' =>  'btn btn-success']) ?>
	</div>
</div>
<?php $form = ActiveForm::begin([
	'id'=>'edit-step-picture',
	'action' => ['editfotostep'],
	'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($editfoto, 'imagefile',['options'=>['style'=>'display:none;'],'errorOptions'=>['class'=>'help-block','id'=>'edit-step-picture-error']])->fileInput() ?>
<?php ActiveForm::end(); ?>



<?php
	
	
	$this->registerJs("
	
	
	$('#step-picture-preview').click(function(){
	$('form#step-picture-form input#uploadfotostep-imagefile').click();
	});
	
	$('#uploadfotostep-imagefile').change(function(){
	$('form#step-picture-form').submit().on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	var formUpload = document.getElementById('step-picture-form');
	$('#step-picture-upload-spinner').show();
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
	$('#step-picture-preview').attr('src',data.url);
	$('#step-step_picture').attr('value',data.filename).trigger('change');;
	
	$('#step-picture-upload-spinner').hide();
	}
	},
	error: function () {
	$('#step-picture-upload-spinner').hide();
	}
	});
	
	return false;
	});
	
	});
	
	$('form#step-form').on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	$('#step-btn').html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Menambhkan Langkah Ini');
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
	data: form.serialize(),
	dataType:'json',
	success: function(data) {
	
	if(data.status == 'created'){
	//window.location.href = data.url;
	form[0].reset();
	$.get('{$homeurl}post/getstep',{postid:'{$postid}'},function(respone){
	$('.step-list').html(respone);
	
	});
	$('form#edit-step-picture')[0].reset();
	$('#step-picture-preview').attr('src','{$homeurl}image/350x140.jpg');
	$('#step-btn').html('<i class=\"fa fa-plus\"></i> Menambhkan Langkah Ini');
	unsaved = false;
	
	}
	},
	error: function () {
	$('#step-btn').html('<i class=\"fa fa-plus\"></i> Menambhkan Langkah Ini');
	alert('Gagal menyimpan!');
	}
	});
	
	return false;
	});
	
	
	
	
	");
?>
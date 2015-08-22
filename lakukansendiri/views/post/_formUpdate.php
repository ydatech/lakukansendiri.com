<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\bootstrap\Modal;
	//use dosamigos\ckeditor\CKEditor;
	use yii\helpers\ArrayHelper;
	use app\models\Category;
	use app\models\Subcategory;
	use yii\helpers\Url;
	
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	/* @var $form yii\widgets\ActiveForm */
	$homeurl = Yii::$app->homeUrl;
?>




<div class="post-update-form">
	
	
    <?php $form = ActiveForm::begin([
		'id'=>'post-update',
	]); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255],['id'=>'update-post-title']) ?>
	<?= $form->field($model, 'post_cover')->hiddenInput()->label(false)?>
	
	<?php // $form->field($model, 'description')->widget(CKEditor::className(), [
		//'options' => ['rows' => 6],
		//'preset' => 'basic'
	//]) ?>
	<?= $form->field($model,'description')->textarea(['rows'=>6,'maxlength'=>350,'id'=>'update-post-description'])?>
	
	<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'category_name'),['id'=>'update-post-category_id'])?>
	
	
	<?= $form->field($model, 'subcategory_id')->dropDownList(ArrayHelper::map(Subcategory::findAll(['category_id'=>$model->category_id]), 'id', 'subcategory_name'),['id'=>'update-post-subcategory_id']) ?>
	<?= $form->field($model, 'status')->dropDownList([$model::STATUS_DRAFTED => 'Konsep',$model::STATUS_PUBLISHED=>'Publik'])?>
	
	<div class="form-group field-post-term" style="display:none;">
		<p>Dengan mempublikasikan instruksi ini, anda setuju dengan kebijakan konten LakukanSendiri.com</p>
	</div>
	
	
	
    <?php ActiveForm::end(); ?>
	<?php $form = ActiveForm::begin([
		'id'=>'foto-cover-form',
		'action' => ['uploadfoto'],
		'options' => ['enctype' => 'multipart/form-data']
	]); ?>
	<?= $form->field($foto, 'imagefile')->fileInput()->label('Foto Sampul') ?>
	<?php ActiveForm::end(); ?>
	
	<div class="form-group field-post-cover-preview">
		<i style="display:none;" class="ajax-loading-icon fa fa-spin fa-cog" id="cover-upload-spinner" ></i>
		<img id="sampul-preview" class="img-responsive" src="<?php echo $model->post_cover?$homeurl.'image/instruksi/thumb_' .$model->post_cover:$homeurl.'image/350x140.jpg'?>" alt="">
		
	</div>
	<div class="form-group">
        <?= Html::submitButton( '<i class="fa fa-pencil"></i> Perbarui Informasi', ['id'=>'update-btn','form'=>'post-update','class' => 'btn btn-primary']) ?>
	</div>
</div>

<?php 
	$url = Url::to('getsubcategory');
	//$this->registerJsFile(Url::to(Yii::$app->homeUrl.'js/jquery.autosave.js'),['depends'=>[\yii\web\JqueryAsset::className()]]);
	
	$this->registerJs("
	
	var unsaved = false;
	
	
	$('#sampul-preview').click(function(){
	$('form#foto-cover-form input#uploadfoto-imagefile').click();
	});
	$('#post-status').change(function(){
	if($(this).val() == '10'){
	$('.field-post-term').show();
	
	}else{
	$('.field-post-term').hide();
	}
	
	});	
	$('#update-post-category_id').change(function(){
	$('#update-post-subcategory_id').load('{$url}',{'cid':$(this).val()},function(){
	//$('.field-post-subcategory_id').show();
	});
	});
	
	$('#uploadfoto-imagefile').change(function(){
	$('form#foto-cover-form').submit().on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	var formUpload = document.getElementById('foto-cover-form');
	$('#cover-upload-spinner').show();
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
	$('#sampul-preview').attr('src',data.url);
	$('#post-post_cover').attr('value',data.filename).trigger('change');;
	
	$('#cover-upload-spinner').hide();
	}
	},
	error: function () {
	$('#cover-upload-spinner').hide();
	}
	});
	
	return false;
	});
	
	});
	$('form#post-update').on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	$('#update-btn').html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Memperbarui Instruksi');
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
	data: form.serialize(),
	dataType:'json',
	success: function(data) {
	
	if(data.status == 'saved'){
	//window.location.href = data.url;
	$('#update-btn').html('<i class=\"fa fa-pencil\"></i> Perbarui Instruksi');
	unsaved = false;
	}
	},
	error: function () {
	$('#update-btn').html('<i class=\"fa fa-pencil\"></i> Perbarui Instruksi');
	alert('Gagal menyimpan!');
	}
	});
	
	return false;
	});
	
	
	$('form#post-update :input,form#step-form :input,form#material-form :input').change(function(){ //trigers change in all input fields including text type
    unsaved = true;
	});
	
	function unloadPage(){ 
    if(unsaved){
	return 'Sepertinya ada perubahan yang belum tersimpan, apakah anda yakin ingin meninggalkan halaman ini tanpa menyimpan perubahan tersebut?';
    }
	}
	
	window.onbeforeunload = unloadPage;
	

	
	");
	
?>
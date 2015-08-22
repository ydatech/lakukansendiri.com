<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	//use dosamigos\ckeditor\CKEditor;

	use yii\helpers\Url;
	
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	/* @var $form yii\widgets\ActiveForm */
?>




<div class="post-material-form">
	
    <?php $form = ActiveForm::begin([
		'id'=>'material-form',
		'action' => ['creatematerial']
		//'options'=>['class'=>'form-inline']
	]); ?>
    <?= $form->field($model, 'material_name')->textInput(['maxlength' => 255,'placeholder'=>'contoh. terigu']) ?>
	<?= $form->field($model, 'post_id')->hiddenInput(['value'=>$postid])->label(false)?>
	<?= $form->field($model, 'material_amount')->textInput(['maxlength' => 255,'placeholder'=>'contoh. 2']) ?>
	<?= $form->field($model, 'material_unit')->textInput(['maxlength' => 255,'placeholder'=>'contoh. kg']) ?>

    <?= Html::submitButton('<i class="fa fa-plus" aria-hidden="true"></i> Tambah Alat dan Bahan', ['class' =>'btn btn-success','id'=>'material-btn']) ?>
	
	
    <?php ActiveForm::end(); ?>
	
</div>

<?php 
	$url =  Yii::$app->homeUrl;//Url::to('getsubcategory');
	$this->registerJs("
	
	
	$('#material-form').on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	$('#material-btn').html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Menambahkan Alat dan Bahan');
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
	data: form.serialize(),
	dataType:'json',
	success: function(data) {
	if(data.status == 'created'){
	//alert(data.status+data.id)
	form[0].reset();
	$.get('{$url}post/getmaterial',{postid:'{$postid}'},function(respone){
	$('.material-list').html(respone);
	
	});
	
		$('#material-btn').html('<i class=\"fa fa-plus\"></i> Tambah Alat dan Bahan');
	unsaved = false;
	}
	},
	error:function(){
	$('#material-btn').html('<i class=\"fa fa-plus\"></i> Tambah Alat dan Bahan');
	}
	});
	
	return false;
	});


	
	");
	
?>

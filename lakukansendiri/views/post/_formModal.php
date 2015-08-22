<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	//use yii\bootstrap\Modal;
	//use dosamigos\ckeditor\CKEditor;
	use yii\helpers\ArrayHelper;
	use app\models\Category;
	use yii\helpers\Url;
	
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	/* @var $form yii\widgets\ActiveForm */
	
?>




<?php $form = ActiveForm::begin([
	'id'=>'create-form',
	'action' => ['create']
]); ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

<?php /*$form->field($model, 'description')->widget(CKEditor::className(), [
	'options' => ['rows' => 6],
	'preset' => 'basic'
]) */ ?>
<?= $form->field($model,'description')->textarea(['rows'=>6,'maxlength'=>350])?>

<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'category_name'),['prompt'=>'- Pilih Kategori -'])?>
<div class="form-group loading-subcategory" style="display:none;">
	<i class="ajax-loading-icon fa fa-spin fa-cog"></i>
</div>

<?= $form->field($model, 'subcategory_id',['options'=>['style'=>'display:none;']])->dropDownList([]) ?>

<div class="form-group">
	<?= Html::submitButton('<i class="fa fa-pencil"></i> Buat Instruksi', ['class' =>'btn btn-info','id'=>'create-instruction-modal-btn']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php 
	$url =  Yii::$app->homeUrl;//Url::to('getsubcategory');
	$this->registerJsFile(Url::to(Yii::$app->homeUrl.'js/charCount.js'),['depends'=>[\yii\web\JqueryAsset::className()]]);
	$this->registerJs("
	
	$('#post-description,#update-post-description').charCount({
	allowed: 350,		
	warning: 300,
	});
	$('#post-category_id').change(function(){
	//alert('hallo');
	$('.loading-subcategory').show();
	$('#post-subcategory_id').load('{$url}post/getsubcategory',{'cid':$(this).val()},function(){
	$('.loading-subcategory').hide();
	$('.field-post-subcategory_id').show();
	
	});
	});
	
	
	$('#create-form').on('beforeSubmit', function(event, jqXHR, settings) {
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	
	$('#create-instruction-modal-btn').html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Membuat Instruksi');
	
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
	data: form.serialize(),
	dataType:'json',
	success: function(data) {
	if(data.status == 'created'){
	$('#create-instruction-modal-btn').html('<i class=\"fa fa-pencil\"></i> Buat Instruksi');
	
	window.location.href = data.url;
	}
	},
	error : function(){
	$('#create-instruction-modal-btn').html('<i class=\"fa fa-pencil\"></i> Buat Instruksi');
	
	}
	});
	
	return false;
	});
	
	
	
	");
	
?>

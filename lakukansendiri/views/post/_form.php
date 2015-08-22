<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	//use yii\bootstrap\Modal;
	//use dosamigos\ckeditor\CKEditor;
	use yii\helpers\ArrayHelper;
	use app\models\Category;
	//use yii\helpers\Url;

	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	/* @var $form yii\widgets\ActiveForm */
	
?>


<?php Modal::begin([
    'header' => '<h2>Buat Instruksi Baru</h2>',
	'id'=>'create-modal',
    'toggleButton' => ['label' => 'click me'],
]);?>

<div class="post-form">
	
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
	
    <?php /*$form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
	]) */ ?>
	<?= $form->field($model,'description')->textarea(['rows'=>6,'maxlength'=>350])?>
	
	<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'category_name'),['prompt'=>'- Pilih Kategori -'])?>
	
	
    <?= $form->field($model, 'subcategory_id',['options'=>['style'=>'display:none;']])->dropDownList([]) ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
    <?php ActiveForm::end(); ?>
	
</div>
<?php Modal::end();?>
<?php 
	$url = Url::to('getsubcategory');
	$this->registerJsFile(Url::to(Yii::$app->homeUrl.'js/charCount.js'),['depends'=>[\yii\web\JqueryAsset::className()]]);
	$this->registerJs("
		$(document).ready(function(){
		$('#post-description').charCount({
			allowed: 350,		
			warning: 300,
		});
		$('#post-category_id').change(function(){
			$('#post-subcategory_id').load('{$url}',{'cid':$(this).val()},function(){
				$('.field-post-subcategory_id').show();
			});
		});
		
		});
	
	");
	
?>
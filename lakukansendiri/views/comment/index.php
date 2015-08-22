<?php
	
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	$homeurl = Yii::$app->homeUrl;
	/* @var $this yii\web\View */
?>
<h4>Tinggalkan Komentar:</h4>

<?php if(!Yii::$app->user->isGuest):?>
<?php $form = ActiveForm::begin([
	'options'=>[
	'class'=>'comment-form',
	],
	'id'=>'comment-form',
	'action' => ['create'],	
	
]); ?>
<?= $form->field($model, 'comment_content')->textarea(['class'=>'form-control', 'rows'=>3])->label(false) ?>
<?= $form->field($model, 'post_id')->hiddenInput(['value'=>$postid])->label(false)?>
<?= $form->field($model, 'comment_parent')->hiddenInput(['value'=>0])->label(false)?>
<button id="comment-btn" type="submit" class="btn btn-primary submit-comment-btn"><i class="fa fa-send"></i> Kirim</button>
<?php ActiveForm::end(); ?>
<div class="comment-form-replace" style="display:none">
	<a href="javascript:void(0)" class="add-parent-comment"> Klik Untuk Menambahkan Komentar</a>
</div>

<?php 
	$this->registerJs("
	$('.add-parent-comment').click(function(){
	$('.comment-form-replace').before($('.comment-form')).hide();
	$('#comment-comment_parent').attr('value','0');
	});
	$('form#comment-form').on('beforeSubmit', function(event, jqXHR, settings) {
	
	var form = $(this);
	if(form.find('.has-error').length) {
	return false;
	}
	$('#comment-btn').html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Mengirim');
	$.ajax({
	url: form.attr('action'),
	type: 'POST',
data: form.serialize(),
dataType:'json',
success: function(data) {

if(data.status == 'created'){
//window.location.href = data.url;
form[0].reset();
$('.comment-form-replace').before($('.comment-form')).hide();
$.get('{$homeurl}comment/get',{postid:'{$postid}'},function(respone){
$('.comment-content').html(respone);

});
$('#comment-btn').html('<i class=\"fa fa-send\"></i> Kirim');

}
},
error: function () {
$('#comment-btn').html('<i class=\"fa fa-send\"></i> Kirim');
alert('Gagal menyimpan!');
}
});

return false;


});

");
?>
<?php endif;?>
<?php 
	//use yii\helpers\Html;
	//use yii\widgets\ActiveForm;
	use dosamigos\editable\Editable;
	use yii\web\JsExpression;
	//use yii\helpers\ArrayHelper;
	$homeurl = Yii::$app->homeUrl;
	$order = array();
	for($i = 1; $i <= count($model); $i++){
		array_push($order, $i);
	}
	$postid ='';
	
?>

<?php foreach($model as $data):?>
<div class="col-lg-12">
	<h2>Langkah ke :
		<?php $postid = $data->post_id;?>
		<?= Editable::widget( [
			'model' => $data,
			
			'attribute' => 'order',
			'url' => 'post/inlineupdatestep',
			'type' => 'text',
			//'type'=>'select',
			//'mode'=>'popup',
			'options'=>[
			'id'=>'step_order_' . $data->id,
			
			],
			'clientOptions' => [
			'emptytext'=>'Edit',
			'showbuttons'=>false,
			'onblur'=>'submit',
			'pk' => $data->id,
			//'value'=>$data->order,
			//'source' =>$order,
			'success'=>new JsExpression("
			function(response, newValue){
			$.get('{$homeurl}post/getstep',{postid:'{$data->post_id}'},function(respone){
		$('.step-list').html(respone);
		
	},'html');
	
	}"),
	
	'placement' => 'right',
	
	]
	]);?>
	</h2>
	
	<hr>
	<a class="btn btn-sm btn-danger deletestep" data-id="<?php echo $data->id;?>" href="javascript:void(0)"><i class="fa fa-trash"></i> Hapus Langkah Ke-<?php echo $data->order;?> Ini</a>
	<hr>
	<!-- Title -->
	Judul :
	<h3>
		<?= Editable::widget( [
			'model' => $data,
			
			'attribute' => 'step_title',
			'url' => 'post/inlineupdatestep',
			'type' => 'text',
			'options'=>[
			'id'=>'step_title_' . $data->id,
			
			],
			'clientOptions' => [
			
			'showbuttons'=>false,
			'onblur'=>'submit',
			'pk' => $data->id,
			
			//'placement' => 'right',
			
			]
		]);?>
	</h3>
	
	
	Klik Foto Untuk Mengedit :
	<p class="text-danger" id="edit-picture-error-<?php echo $data->id;?>"></p>
	<!-- Preview Image -->
	<i style="display:none;" class="ajax-loading-icon fa fa-spin fa-cog" id="edit-step-picture-spinner-<?php echo $data->id;?>" ></i>
	
	<img title="klik untuk mengedit" id="edit-step-picture-preview-<?php echo $data->id;?>" class="img-responsive edit-picture" data-id="<?php echo $data->id;?>" src="<?php echo  $data->step_picture ? $homeurl.'image/step/thumb_'.$data->step_picture : $homeurl.'image/350x140.jpg';?> " alt="">
	
	<hr>
	
	Video :
	<p class="text-danger" id="edit-video-error-<?php echo $data->id;?>"></p>
	<p>
		<?= Editable::widget( [
			'model' => $data,
			
			'attribute' => 'step_video',
			'url' => 'post/inlineupdatestep',
			'type' => 'text',
			'options'=>[
			'id'=>'step_video_' . $data->id,
			'data-id'=>$data->id
			
			],
			'clientOptions' => [
			'emptytext'=>'Edit',
			'showbuttons'=>false,
			'onblur'=>'submit',
			'success'=>new JsExpression("
			function(response, newValue){
			var eid = $(this).attr('data-id');
			var yid = youtube_parser(newValue);
			if(yid){
			$('#embed-step-video-'+eid).attr('src','http://www.youtube.com/embed/'+yid);
			$('#edit-video-error-'+eid).text('');
			}else{
			$('#edit-video-error-'+eid).text('Bukan URL Youtube yang benar');
			}
			}
			"),
			'pk' => $data->id,
			
			//'placement' => 'right',
			
			]
		]);?>
	</p>
	<?php if($data->step_video):?>
	<iframe width="350" height="200" frameborder="0" id="embed-step-video-<?php echo $data->id;?>"
	src="http://www.youtube.com/embed/<?php echo $data->step_video;?>">
	</iframe>
	<?php else:?>
	<iframe width="350" height="200"  id="embed-step-video-<?php echo $data->id;?>"
	src="">
	</iframe>
	<?php endif;?>
	<hr>
	Deskripsi :
	<!-- Post Content -->
	<p>
		<?= Editable::widget( [
			'model' => $data,
			
			'attribute' => 'step_description',
			'url' => 'post/inlineupdatestep',
			'type' => 'textarea',
			'options'=>[
			'id'=>'step_description_' . $data->id,
			],
			'clientOptions' => [
			'showbuttons'=>false,
			'onblur'=>'submit',
			'pk' => $data->id,
			//'placement' => 'right',
			
			
			]
		]);?>
	</p>
	
	<hr>
	
</div>

<?php endforeach;?>	

<?php 
	
	$this->registerCss("
	.editable-container.editable-inline,
	.editable-container.editable-inline .control-group.form-group,
	.editable-container.editable-inline .control-group.form-group .editable-input,
	.editable-container.editable-inline .control-group.form-group .editable-input textarea,
	.editable-container.editable-inline .control-group.form-group .editable-input select,
	.editable-container.editable-inline .control-group.form-group .editable-input input:not([type=radio]):not([type=checkbox]):not([type=submit])
	{
    width: 100%;
	}
	h2 .editable-error-block{
	font-size : 14px;
	}
	");
	
	$this->registerJs("
	function youtube_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
	return match[7];
    }else{
	return false;
    }
	}
	
	var stepid = '';
	$('.deletestep').click(function(){
	var c = confirm('Apakah anda yakin anda ingin menghapus langkah ini?');
	var sid = $(this).attr('data-id');
	if(c){
	
	$.post('{$homeurl}post/deletestep',{sid:sid},function(){
	$.get('{$homeurl}post/getstep',{postid:'{$postid}'},function(respone){
		$('.step-list').html(respone);
		
	},'html');
	
	});
	}
	
	});
	$('.edit-picture').click(function(){
	$('#editfotostep-imagefile').click();
	stepid = $(this).attr('data-id');
	});
	$('#editfotostep-imagefile').change(function(){
	$('form#edit-step-picture').submit().on('afterValidate',function(e, msg, parentEvent){
	var errmessage = $('div#edit-step-picture-error').text();
	if(errmessage.length){
	$('#edit-picture-error-'+stepid).text(errmessage);
	}
	
	
	}).on('beforeSubmit', function(event, jqXHR, settings) {
	
	
	
	var form = $(this);
	
	if(form.find('.has-error').length) {	
	return false;
	}
	var formUpload = document.getElementById('edit-step-picture');
	$('#edit-step-picture-spinner-'+stepid).show();
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
	
	$.post('{$homeurl}post/updatefotostep',{sid:stepid,newpic:data.filename},function(respone){
	if(respone.status == 'saved'){
	$('#edit-step-picture-preview-'+stepid).attr('src',data.url);
	}
	},'json');
	
	//$('#step-step_picture').attr('value',data.filename).trigger('change');
	
	$('#edit-step-picture-spinner-'+stepid).hide();
	}
	},
	error: function () {
	$('#edit-step-picture-spinner-'+stepid).hide();
	}
	});
	
	return false;
	});
	
	});
	
	");
?>
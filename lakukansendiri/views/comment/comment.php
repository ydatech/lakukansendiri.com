<?php use yii\helpers\Url;?>
<?php if(!$model == null):?>
<?php foreach($model as $comment):?>
<div class="media" id="comment-<?= $comment->id?>">
	<a class="pull-left" href="<?= Url::to(['app/user','u'=>$comment->user->username])?>">
		<img width="64px" class="media-object img-circle" src="<?= $comment->user->avatar ?>" alt="">
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?= Url::to(['app/user','u'=>$comment->user->username])?>" ><?= $comment->user->displayName ?></a>
			<small><?= $comment->indonesiancalender;?></small>
		</h4>
		<?php if(!Yii::$app->user->isGuest && $comment->user->id == Yii::$app->user->id):?>
		<?= \dosamigos\editable\Editable::widget( [
			'model' => $comment,
			
			'attribute' => 'comment_content',
			'url' => 'post/inlineupdatecomment',
			'type' => 'textarea',
			'options'=>[
			'id'=>'comment_content_' . $comment->id,
			],
			'clientOptions' => [
			'rows'=>3,
			'showbuttons'=>false,
			'onblur'=>'submit',
			'pk' => $comment->id,
			//'placement' => 'right',
			
			
			]
		]);?>
		<p class="pull-right"><a class="reply-comment"  rel="nofollow"  href="javascript:void(0)" data-parent="<?= $comment->id?>"><i class="fa fa-reply"></i> </a> | <a class="delete-comment" href="javascript:void(0)" data-id="<?= $comment->id?>"><i class="fa fa-trash"></i>  </a></p>
		
		<?php else:?>
		<?= $comment->comment_content?>
		<!-- Nested Comment -->
		<p class="pull-right"><a class="reply-comment"  rel="nofollow" href="javascript:void(0)" data-parent="<?= $comment->id?>"><i class="fa fa-reply"></i> </a></p>
		<?php endif;?>
		<hr>
		<?php foreach($comment->reply as $reply):?>
		
		<div class="media" id="comment-<?= $reply->id?>">
			<a class="pull-left"   href="<?= Url::to(['app/user','u'=>$reply->user->username])?>">
				<img width="64px" class="media-object img-circle" src="<?= $reply->user->avatar?>" alt="">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?= Url::to(['app/user','u'=>$reply->user->username])?>"><?= $reply->user->displayName?></a>
					<small><?= $reply->indonesiancalender?></small>
				</h4>
				
				<?php if(!Yii::$app->user->isGuest && $reply->user->id == Yii::$app->user->id):?>
				<?= \dosamigos\editable\Editable::widget( [
					'model' => $reply,
					
					'attribute' => 'comment_content',
					'url' => 'post/inlineupdatecomment',
					'type' => 'textarea',
					'options'=>[
					'id'=>'reply_comment_content_' . $reply->id,
					],
					'clientOptions' => [
					'rows'=>3,
					'showbuttons'=>false,
					'onblur'=>'submit',
					'pk' => $reply->id,
					//'placement' => 'right',
					
					
					]
				]);?>
				
				<p class="pull-right"><a class="delete-comment"   rel="nofollow" href="javascript:void(0)" data-id="<?= $reply->id?>"><i class="fa fa-trash"></i>  </a></p>
				
				<?php else:?>
				<?= $reply->comment_content?>
				<!-- Nested Comment -->
				
				<?php endif;?>
				
			</div>
		</div>
		<hr>
		<?php endforeach;?>
		<?php $total_reply = ceil($comment->replypagination->totalCount/$comment->replypagination->pageSize)?>
		<?php if ($total_reply > 1):?>
		<div class="text-center more-comment-btn-container-<?= $comment->id?>" >
			<a href="javascript:void(0)"  rel="nofollow"  data-loading="false" data-totalreply="<?= $total_reply?>" data-trackload="2" data-parent="<?= $comment->id ?>" class="more-comment-reply-btn" id="more-comment-btn-<?= $comment->id?>">
				<i class="fa fa-arrow-down"></i> Muat Lebih Banyak
			</a>
			<hr>
		</div>
		
		<?php endif;?>
		<div class="nested-comment-form-<?= $comment->id?>">
		</div>
		<script type="text/javascript">
			new_total_reply_groups = '<?php echo $total_reply; ?>';
			total_reply_posts = '<?php echo $comment->replypagination->totalCount; ?>';
			
		</script>
		<!-- End Nested Comment -->
	</div>
	<hr>
</div>



<?php endforeach;?>

<?php //echo $data->totalCount;
	$new_total_groups = ceil($pages->totalCount / $pages->pageSize);
?>
<?php if($new_total_groups > 1):?>
<div class="text-center more-comment-btn-container">
	<a href="javascript:void(0)"  rel="nofollow"  class="more-comment-btn" id="more-comment-btn">
		<i class="fa fa-arrow-down"></i> Muat Lebih Banyak
	</a>
</div>
<?php endif;?>
<script type="text/javascript">
	new_total_groups = '<?php echo $new_total_groups; ?>';
    total_posts = '<?php echo $pages->totalCount; ?>';
	
</script>
<?php else:?>
<p>Tidak Ada Komentar</p>
<?php endif;?>

<?php 
	
	$homeurl = Yii::$app->homeUrl;
	
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
	$('.reply-comment').click(function(){
	var cid = $(this).attr('data-parent');
	$('#comment-comment_parent').attr('value',cid);
	$('.comment-form-replace').show();
	$('.nested-comment-form-'+cid).before($('.comment-form'));
	});
	$('.delete-comment').click(function(){
	var id = $(this).attr('data-id');
	var c = confirm('Apakah anda yakin ingin menghapus komentar ini?');
	if(c){
	$.post('{$homeurl}comment/delete',{'id':id},function(respone){
	if(respone.status == 'deleted'){
	
	$('#comment-'+id).hide();
	/*
	$.get('{$homeurl}comment/get',{postid:{$postid}},function(comment){
	$('.comment-content').html(comment);
	});
	*/
	}
	},'json');
	
	}
	
	});
	
	
	$('.more-comment-reply-btn').click(function(){
	//alert('hello');
	var btn = $(this);
	var parent = $(this).attr('data-parent');
	var track_load_reply = parseInt($(this).attr('data-trackload'));
	var loadingreply = $(this).attr('data-loading');
	var new_total_reply_groups = $(this).attr('data-totalreply');
	if(track_load_reply <= new_total_reply_groups && loadingreply == 'false') //there's more data to load
	{
	$(this).attr('data-loading','true');//prevent further ajax loading
	$(btn).html('<i class=\"ajax-loading-icon fa fa-spin fa-cog\"></i> Sedang Memuat');
	
	$.get('{$homeurl}comment/reply',{'postid':'{$postid}','parent':parent,'page':track_load_reply},function(respone){
	$('.more-comment-btn-container-'+parent).before(respone);
	$(this).attr('data-trackload',track_load_reply++);
	$(this).attr('data-loading','false');
	$(btn).html('<i class=\"fa fa-arrow-down\"></i> Muat Lebih Banyak');
	
	}).fail(function(xhr, ajaxOptions, thrownError) {
	
	$(this).attr('data-loading','false');
	$(btn).html('<i class=\"fa fa-arrow-down\"></i> Muat Lebih Banyak');
	});
	}
	else{
	
	btn.html('Balasan Komentar Sudah Dimuat Semua');
	}
	
	});
	
	
	
	
	");
?>
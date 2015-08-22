<?php if(!$model == null):?>
<?php foreach($model as $comment):?>
<div class="media">
	<a class="pull-left" href="<?= Url::to(['app/user','u'=>$comment->user->username])?>">
		<img width="64px" class="media-object img-circle" src="<?= $comment->user->avatar ?>" alt="">
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="<?= Url::to(['app/user','u'=>$comment->user->username])?>"><?= $comment->user->displayName ?></a>
			<small><?= $comment->indonesiancalender;?></small>
		</h4>
		<?php if(!Yii::$app->user->isGuest && $reply->user->id == Yii::$app->user->id):?>
				<?= \dosamigos\editable\Editable::widget( [
					'model' => $comment,
					
					'attribute' => 'comment_content',
					'url' => 'post/inlineupdatecomment',
					'type' => 'textarea',
					'options'=>[
					'id'=>'reply_comment_content_' . $comment->id,
					],
					'clientOptions' => [
					'rows'=>3,
					'showbuttons'=>false,
					'onblur'=>'submit',
					'pk' => $comment->id,
					//'placement' => 'right',
					
					
					]
				]);?>
				
				<p class="pull-right"><a class="delete-comment" rel="nofollow" href="javascript:void(0)" data-id="<?= $comment->id?>"><i class="fa fa-trash"></i>  </a></p>
				
				<?php else:?>
				<?= $comment->comment_content?>
				<!-- Nested Comment -->
				
				<?php endif;?>
	</div>
	<hr>
</div>

<?php endforeach;?>
<?php endif;?>
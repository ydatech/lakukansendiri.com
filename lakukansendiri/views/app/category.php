<?php 
	use yii\helpers\Url;
	?>
<?php foreach($model as $data):?>

<ul class="col-sm-2 list-unstyled">
	<li>
		<p><strong><a href="<?= Url::to(['app/category','cat'=>$data->category_code,'sub'=>''])?>"><?php echo $data->category_name;?></a></strong></p>
	</li>
	<?php foreach($data->subcategories as $subdata):?>
	<li><a href="<?= Url::to(['app/category','cat'=>$data->category_code,'sub'=>$subdata->subcategory_code])?>"> <?php echo $subdata->subcategory_name;?> </a></li>
	<?php endforeach;?>
	
</ul>

<?php endforeach;?>
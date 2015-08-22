<?php 
	use dosamigos\editable\Editable;
?>
<div class="materials">
	<h4> <?php echo count($model);?> Alat dan Bahan</h4>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					No
				</th>
				<th>
					Nama Alat atau Bahan
				</th>
				<th>
					Jumlah
				</th>
				<th>
					Satuan
				</th>
				<th>
					Action
				</th>
			</tr>
			<thead>
				<tbody>
					<?php 
						$no = 1;
					foreach($model as $data):?>
					<tr>
						<td>
							<?php 
								echo $no;
								$no++;
							?>
						</td>
						<td>
							<?= Editable::widget( [
								'model' => $data,
								
								'attribute' => 'material_name',
								'url' => 'post/inlineupdatematerial',
								'type' => 'text',
								
								'options'=>[
								'id'=>'material_name_' . $data->id,
								],
								'clientOptions' => [
								'showbuttons'=>false,
								'onblur'=>'submit',
								'pk' => $data->id,
								//'placement' => 'right',
								
								]
							]);?>	
						</td>
						<?php //echo $data->material_name;?>
						<td>
							<?= Editable::widget( [
								'model' => $data,
								
								'attribute' => 'material_amount',
								'url' => 'post/inlineupdatematerial',
								'type' => 'text',
								
								'options'=>[
								'id'=>'material_amount_' . $data->id,
								],
								'clientOptions' => [
								'showbuttons'=>false,
								'onblur'=>'submit',
								'pk' => $data->id,
								//'placement' => 'right',
								
								]
							]);?>
							</td>
						<td>
							
							<?= Editable::widget( [
								'model' => $data,
								
								'attribute' => 'material_unit',
								'url' => 'post/inlineupdatematerial',
								'type' => 'text',
								
								
								'options'=>[
								'id'=>'material_unit_' . $data->id,
								],
								'clientOptions' => [
								'showbuttons'=>false,
								'onblur'=>'submit',
								'pk' => $data->id,
								//'placement' => 'right',
								
								]
							]);?>
							</td>
							<td>
							<a href="javascript:void(0)" title="Hapus" class="delete-material" data-id="<?php echo $data->id;?>" ><span class="glyphicon glyphicon-trash"></span></a>
							</td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
<?php 
	$url = Yii::$app->homeUrl;
	$this->registerJs("
	$(document).ready(function(){
	$('.delete-material').click(function(){
	
	var mid = $(this).attr('data-id');
	
	
	var k = confirm('Apakah anda yakin ingin menghapus ini?');
	if(k){
	
	$.post('{$url}post/deletematerial',{'mid':mid},function(respone){
		if(respone.status == 'deleted'){
		
		$.get('{$url}post/getmaterial',{postid:respone.postid},function(respone){
				$('.material-list').html(respone);
	
			});
		}
	
	},'json');
	}
	
	});
	
	});
	
	");
	?>		
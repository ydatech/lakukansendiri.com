<?php if(!$model == null):?>
<h2>Alat dan Bahan</h2>
<hr>
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
						<?= $data->material_name?>	
					</td>
					<?php //echo $data->material_name;?>
					<td>
						<?= $data->material_amount?>
					</td>
					<td>
						
						<?= $data->material_unit?>
					</td>
					
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		<hr>
	<?php endif;?>	
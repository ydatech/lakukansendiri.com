<?php
	
	use yii\helpers\Html;
	use yii\helpers\Url;
	/* @var $this yii\web\View */
	/* @var $model app\models\Post */
	
	$this->title = 'Edit Instruksi: ' . ' ' . $model->title;
	$this->params['breadcrumbs'][] = ['label' => 'Instruksi Saya', 'url' => ['index']];
	//$this->params['breadcrumbs'][] = ['label' =>''. $model->title, 'url' => ['view', 'id' => $model->id,'slug'=>$model->title]];
	$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="post-update">
	<h1><?= Html::encode($this->title) ?></h1>
	<hr>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" id="updateTab" role="tablist">
		<li role="presentation" class="active"><a href="#basic" aria-controls="home" role="tab" data-toggle="tab">Informasi Dasar</a></li>
		<li role="presentation"><a href="#materials" aria-controls="profile" role="tab" data-toggle="tab">Alat dan Bahan</a></li>
		<li role="presentation"><a href="#steps" aria-controls="messages" role="tab" data-toggle="tab">Langkah-langkah</a></li>
		
	</ul>
	<div class="clearfix"></div>
	<!-- Tab panes -->
	<div id="updateTabContent" class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="basic">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">Informasi Dasar</h2>
				</div>
			</div>
			<?= $this->render('_formUpdate', [
				'model' => $model, 'foto'=>$modelUploadFoto
			]) ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="materials">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">Alat dan Bahan</h2>
				</div>
			</div>
			<div class="col-lg-4">
				<?= $this->render('_formMaterial', [
					'model' => $modelMaterial,
					'postid'=>$model->id
				]) ?>
			</div>
			<div class="col-lg-8 material-list">
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="steps">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">Tambahkan Langkah-langkah</h2>
				</div>
				
			</div>
			<?= $this->render('_formStep', [
				'model' => $modelStep,
				'postid'=>$model->id, 
				'foto'=>$modelUploadFotoStep,
				'editfoto'=>$modelEditFotoStep
			]) ?>
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">Daftar Langkah-langkah untuk Instruksi Ini</h2>
				</div>
				
			</div>
			<div class="step-list" >
			</div>
		</div>
		
	</div>
	
	
	
</div>
<div class="clearfix"></div>
<div>
	<hr>
	<p class="text-center">
		<?php if($model->status == $model::STATUS_PUBLISHED):?>
		<?= Html::a('<i class="fa fa-eye"></i> Lihat Instruksi Ini',Url::to(['view','id'=>$model->id,'slug'=>$model->slug]),['class'=>'btn btn-success','target'=>'_blank'])?>
		<?php else:?>
		<?= Html::a('<i class="fa fa-eye"></i> Preview Instruksi Ini',Url::to(['preview','id'=>$model->id]),['class'=>'btn btn-success','target'=>'_blank'])?>
		
		<?php endif;?>
		<?= Html::a('<i class="fa fa-trash"></i> Hapus Instruksi Ini',Url::to(['delete','id'=>$model->id]),['class'=>'btn btn-danger','data'=>['method'=>'post','confirm'=>'Apakah anda yakin ingin menghapus instruksi ini?']])?>
	</p>
	<hr>
</div>
<div class="loading-overlay" >
	<i class="ajax-loading-icon fa fa-spin fa-cog fa-2x white" id="spinner-filter" ></i>
	<span>please wait...</span>
</div>
<?php
	$homeurl = Yii::$app->homeUrl;
	
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/view.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    //'media' => 'print',
	], 'css-landingpage');
	$this->registerJs("
	$.get('{$homeurl}post/getmaterial',{postid:'{$model->id}'},function(respone){
	$('.material-list').html(respone);
	
},'html');

$.get('{$homeurl}post/getstep',{postid:'{$model->id}'},function(respone){
$('.step-list').html(respone);

},'html');



");
?>
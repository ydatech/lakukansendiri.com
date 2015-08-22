<?php
	
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	
	/* @var $this yii\web\View */
	/* @var $searchModel app\models\PostSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */
	
	$this->title = 'Instruksi Saya';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
	
    <h1><?= Html::encode($this->title) ?></h1>
	<hr>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterPosition'=>false,
        'columns' => [
		['class' => 'yii\grid\SerialColumn','header'=>'No'],
		
		[
		'header'=>'Judul',
		'value'=>'title'
		],
		[
		'header'=>'Status',
		'format'=>'html',
		'value'=>function($model,$key,$index,$column){ return ($model->status == \app\models\Post::STATUS_PUBLISHED)?'<span class="label label-success">Publik</span>':'<span class="label label-default">Konsep</span>';}
		],
		
		// 'category_id',
		// 'subcategory_id',
		// 'created_at',
		// 'updated_at',
		
		['class' => 'yii\grid\ActionColumn',
		'header'=>'Actions',
		'template'=>'{view} {update} {delete}',
		'buttons'=>[ 'delete' => function($url,$data,$key){
			return Html::a('<i class="fa fa-trash"></i>',Url::to(['delete','id'=>$data->id]),['title'=>'Hapus','data'=>['method'=>'post','confirm'=>'Apakah Anda yakin ingin menghapus instruksi ini?']]);	
		},
		'view'=>function($url,$data,$key){
			return ($data->status == \app\models\Post::STATUS_PUBLISHED)?Html::a('<i class="fa fa-eye"></i>',Url::to(['view','id'=>$data->id,'slug'=>$data->slug]),['title'=>'Lihat']):'';
		},
		'update'=>function($url,$data,$key){
			return Html::a('<i class="fa fa-pencil"></i>',Url::to(['update','id'=>$data->id]),['title'=>'Edit']);
			}
		],
		
		
		
		
		],
        ],
		]); ?>
		
</div>
<?php 
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/view.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    //'media' => 'print',
	], 'css-landingpage');
?>
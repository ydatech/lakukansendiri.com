<?php
	use yii\helpers\Url;
	/* @var $this yii\web\View */
	$this->title = 'LakukanSendiri.com | Semua Instruksi Oleh '.$u;
	$homeurl = Yii::$app->homeUrl;
?>
<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Semua Instruksi
			<small> oleh user </small>
			
			<?= $u ?>
			<small>(ditemukan <?= $count//Yii::$app->formatter->asDecimal($pages->totalCount) ?> instruksi)</small>
		</h1>
		<hr>
	</div>
	
</div>

<div class="latest">
</div>

<hr>
<div class="loading-overlay" >
	<i class="ajax-loading-icon fa fa-spin fa-cog fa-2x white" id="spinner-filter" ></i>
	<span>please wait...</span>
</div>
<?php 
	$this->registerJsFile(Url::to(Yii::$app->homeUrl.'js/typed.js'),['depends'=>[\yii\web\JqueryAsset::className()]]);
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/homepage.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
	//'media' => 'print',
	], 'css-landingpage');
	$homeurl = Yii::$app->homeUrl;//.'post/view?id=';
$this->registerJs("
$(document).ready(function(){
var track_load = 2; 
var loading  = true; 
//$('.latest-spinner').show();
$.get('{$homeurl}app/postbyauthor',{u:'{$u}'},function(respone){
$('.latest').html(respone);
//$('.latest-spinner').hide();
loading = false;
});
$(window).scroll(function() { //detect page scroll

if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
{

if(track_load <= new_total_groups && loading==false) //there's more data to load
{
loading = true; //prevent further ajax loading
$('.latest-spinner').show(); //show loading image


$.get('{$homeurl}app/postbyauthor',{'u':'{$u}','page':track_load},function(respone){
$('.latest').append(respone);
track_load++;
$('.latest-spinner').hide();
loading = false;
}).fail(function(xhr, ajaxOptions, thrownError) {
$('.latest-spinner').hide();
loading = false;
});
}
}
});

});

");


?>

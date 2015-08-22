<?php
	use yii\helpers\Url;
	/* @var $this yii\web\View */
	$this->title = 'LakukanSendiri.com';
	$homeurl = Yii::$app->homeUrl;
?>
<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Jadilah Kreatif
			<small>dengan membuat</small>
			<form id="home-search-form" action="<?= $homeurl?>search" class="form-inline" style="display:inline">
				<div class="input-group" >
					<label class="sr-only" for="keyword">Pencarian</label>
					<input type="text" class="form-control" name="k" id="keyword" autofocus required></input>
					<div class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</form>
		</h1>
		<hr>
	</div>
	
</div>
<div class="row">
	<div class="col-lg-12 text-center">
		<h1>Apa Itu LakukanSendiri.com?</h1>
		<p class="lead">LakukanSendiri.com adalah situs untuk berbagi instruksi tentang apa yang bisa kamu lakukan, buat dan ulik sendiri!</p> <p class="lead">Tunggu apa lagi, ayo bagikan kreatifitasmu dengan <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#create-modal"> <i class="fa fa-pencil" aria-hidden="true"></i> Membuat Instruksi Baru! </button></p>
		
	</div>
</div>
<!-- /.row -->
<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">Instruksi Terbaru</h2>
		<hr>
	</div>
</div>
<div class="latest-instruction">
	
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
$('#keyword').typed({
strings: ['Semur Jengkol', 'Arduino','Kue Salju'],
typeSpeed: 50,
startDelay: 500,
backSpeed: 50,
attr:'placeholder',
contentType:'text',
loop:true,});

/*.keypress(function(e) {
var keyword = $(this).val();
if(e.which == 13 && keyword.trim().length > 0 ) {
//alert('You type :'+keyword);
window.location.href = '{$homeurl}post/view?id='+keyword;
}
});
*/

var track_load = 2; 
var loading  = true; 
//$('.latest-spinner').show();
$('.latest-instruction').load('{$homeurl}post/latest',function(){
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


$.get('{$homeurl}post/latest',{'page':track_load},function(respone){
$('.latest-instruction').append(respone);
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

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
	


	
		<?php $authAuthChoice = AuthChoice::begin([
			'baseAuthUrl' => ['app/auth'],
			'popupMode' => true,
		]); ?>
			<?php foreach ($authAuthChoice->getClients() as $client): ?>
			<?php switch($client->getId()):
					case 'facebook':?>
			<?php $authAuthChoice->clientLink($client,'<i class="fa fa-facebook"></i> ' . ucfirst($client->getId()),['class'=>'btn btn-info']) ?>
			<?php break;?>
			<?php case 'google':?>
				<?php $authAuthChoice->clientLink($client,'<i class="fa fa-google-plus"></i> '.ucfirst($client->getId()),['class'=>'btn btn-danger']) ?>
		
			<?php break;?>
			<?php endswitch;?>
			<?php endforeach; ?>
	
		<?php AuthChoice::end(); ?>

<!--
    <p>Please fill out the following fields to login:</p>

    <?php /* $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
		
		])->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); */?>

    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>
	-->
</div>
<?php 
	$this->registerCssFile(Url::to(Yii::$app->homeUrl.'css/site.css'), [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
	//'media' => 'print',
	], 'css-landingpage');
	?>

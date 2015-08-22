<?php
	
	$params = require(__DIR__ . '/params.php');
	
	$config = [
    'id' => 'lakukansendiri',
    'basePath' => dirname(__DIR__),
	'defaultRoute'=>'app',
	'language'=>'id',
    'bootstrap' => ['log'],
    'components' => [
	'authClientCollection' => [
	'class' => 'yii\authclient\Collection',
	'clients' => [
	'facebook' => [
	'class' => 'yii\authclient\clients\Facebook',
	'clientId' => '1558005224437450',
	'clientSecret' => '59c79298e7389d49d93d6f2c13a97a7e',
	'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
	'scope'=>'email,public_profile'
	],
	'google' => [
	'class' => 'yii\authclient\clients\GoogleOAuth',
	'clientId' => '652505452797-k1os9t1ns27tl81g09tmqgkulmdjcsf7.apps.googleusercontent.com',
	'clientSecret' => '_9LAgt9S-X-CBSFeWujQftgV',
	],
	// etc.
	],
    ],
	'request' => [
	// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
	'cookieValidationKey' => 'vV7vSOJA05yPtSE-PELkRBFbZ-sHmTu1',
	],
	'cache' => [
	'class' => 'yii\caching\FileCache',
	],
	'user' => [
	'identityClass' => 'app\models\User',
	'enableAutoLogin' => true,
	'loginUrl'=>['app/login']
	],
	'errorHandler' => [
	'errorAction' => 'app/error',
	],
	'mailer' => [
	'class' => 'yii\swiftmailer\Mailer',
	// send all mails to a file by default. You have to set
	// 'useFileTransport' to false and configure a transport
	// for the mailer to send real emails.
	'useFileTransport' => true,
	],
	'log' => [
	'traceLevel' => YII_DEBUG ? 3 : 0,
	'targets' => [
	[
	'class' => 'yii\log\FileTarget',
	'levels' => ['error', 'warning'],
	],
	],
	],
	'db' => require(__DIR__ . '/db.php'),
	'urlManager' => [
	'enablePrettyUrl' => true,
	'showScriptName' => false,
	
	'rules' => [
	// ...
	//'instruksi/<id:\d+>/<slug>'=>'post/view',
	'edit'=>'post/update',
	'user/<u>'=>'app/user',
	[
	'pattern'=>'instruksi/<id:\d+>/<slug>',
	'route'=>'post/view',
	'defaults'=>['id'=>0,'slug'=>'']
	],
	'setting'=>'app/setting',
	'search'=>'app/search',
	'instruksisaya'=>'post/index',
	'author/<u>'=>'app/author',
	[
	'pattern' => 'kategori/<cat>/<sub>',
	'route' => 'app/category',
	'defaults' => ['cat'=>'','sub' =>''],
    ],
	
	],
	
	],
    ],
    'params' => $params,
	];
	
	if (YII_ENV_DEV) {
		// configuration adjustments for 'dev' environment
		$config['bootstrap'][] = 'debug';
		$config['modules']['debug'] = 'yii\debug\Module';
		
		$config['bootstrap'][] = 'gii';
		$config['modules']['gii'] = 'yii\gii\Module';
	}

return $config;

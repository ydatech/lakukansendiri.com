<?php
	
	namespace app\controllers;
	
	use Yii;
	use yii\filters\AccessControl;
	use yii\filters\VerbFilter;
	use app\models\PostInfo;
	use yii\helpers\Json;
	class PostInfoController extends \yii\web\Controller
	{
		public function behaviors()
		{
			return [
			'access' => [
			'class' => AccessControl::className(),
			//'only' => ['inlineupdatematerial','delete',''],
			'rules' => [
			[
			// 'actions' => ['logout','about'],
			'allow' => true,
			'roles' => ['@'],
			],
			
			],
            ],
			
            'verbs' => [
			'class' => VerbFilter::className(),
			'actions' => [
			'favorit' => ['post'],
			
			],
            ],
			
			];
		}
		
		public function actionIndex()
		{
			//return $this->render('index');
		}
		public function actionFavorit(){
			$model = new PostInfo;
			$model->post_id = Yii::$app->request->post('postid');
			$model->type = PostInfo::FAVORIT;
			$model->user_id = Yii::$app->user->id;
			if($model->save()){
				echo Json::encode(array('status'=>'favorited','id'=>$model->id));
				
			}
			else{
				echo Json::encode(array('status'=>'failed'));
				//print_r($model->getErrors());
			}
			
		}
		
		public function actionUnfavorit(){
			$model = PostInfo::find()->where(['post_id'=>Yii::$app->request->post('postid'),'user_id'=>Yii::$app->user->id,'type'=>PostInfo::FAVORIT])->one();
			
			if(!$model == null && $model->delete()){
				echo Json::encode(array('status'=>'unfavorited'));
				
			}
			else{
				echo Json::encode(array('status'=>'failed'));
			}
			
		}
		
		public function actionMade(){
			
			$model = new PostInfo;
			$model->post_id = Yii::$app->request->post('postid');
			$model->type = PostInfo::MADE;
			$model->user_id = Yii::$app->user->id;
			if($model->save()){
				echo Json::encode(array('status'=>'made','id'=>$model->id));
				
			}
			else{
				echo Json::encode(array('status'=>'failed'));
				//print_r($model->getErrors());
			}
		}
		
		public function actionUnmade(){
			$model = PostInfo::find()->where(['post_id'=>Yii::$app->request->post('postid'),'user_id'=>Yii::$app->user->id,'type'=>PostInfo::MADE])->one();
			
			if(!$model == null && $model->delete()){
				echo Json::encode(array('status'=>'unmade'));
				
			}
			else{
				echo Json::encode(array('status'=>'failed'));
			}
			
		}
		
	}

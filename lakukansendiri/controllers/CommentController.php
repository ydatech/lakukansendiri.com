<?php
	
	namespace app\controllers;
	
	use Yii;
	use app\models\Comment;
	use yii\web\NotFoundHttpException;
	use yii\data\Pagination;
	use yii\helpers\Json;
	use yii\filters\AccessControl;
	
	class CommentController extends \yii\web\Controller
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
			[	'actions'=>['get','reply'],
			'allow'=>true,
			'roles'=>['?'],
			
			]
			],
            ],
			
			
			
			];
		}
		public function actionIndex($postid)
		{
			$model = new Comment();
			return $this->renderAjax('index',['model'=>$model,'postid'=>$postid]);
		}
		
		public function actionCreate(){
			$model = new Comment();
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				echo Json::encode(array('status'=>'created','id'=>$model->id));
			}
			
		}
		public function actionGet($postid){
			$queryStep = Comment::find()->where(['post_id' => $postid,'comment_parent'=>0])->orderBy(['created_at'=>SORT_DESC]);
			$countQuery = clone $queryStep;
			$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>5]);
			$model = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
			
			return $this->renderAjax('comment',['model'=>$model,'pages'=>$pages,'postid'=>$postid]);
			
		}
		
		public function actionReply($postid,$parent){
			$queryStep = Comment::find()->where(['post_id' => $postid,'comment_parent'=>$parent])->orderBy(['created_at'=>SORT_DESC]);
			$countQuery = clone $queryStep;
			$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>3]);
			$model = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
			
			return $this->renderAjax('reply',['model'=>$model,'pages'=>$pages,'postid'=>$postid]);
			
			
		}
		
		public function actionDelete(){
		if($id = Yii::$app->request->post('id')){
		$model = Comment::findOne($id);
		if($model->user_id == Yii::$app->user->id && $model->delete()){
		$reply = Comment::deleteAll(['comment_parent'=>$model->id]);
		echo Json::encode(array('status'=>'deleted','id'=>$model->id,'postid'=>$model->post_id));	
		}
		else{
		echo Json::encode(array('status'=>'failed'));
		
		}
		
		}else{
		throw new NotFoundHttpException('The requested page does not exist.');
		}
		}
		
		
		
	}

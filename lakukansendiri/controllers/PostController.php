<?php
	
	namespace app\controllers;
	
	use Yii;
	use app\models\Post;
	use app\models\PostSearch;
	use app\models\Subcategory;
	use app\models\UploadFoto;
	use app\models\UploadFotoStep;
	use app\models\EditFotoStep;
	use app\models\PostHits;
	use yii\web\Controller;
	use yii\web\NotFoundHttpException;
	use yii\filters\AccessControl;
	use yii\filters\VerbFilter;
	use yii\helpers\Html;
	use yii\helpers\Json;
	use yii\helpers\Url;
	use yii\data\ActiveDataProvider;
	use app\models\Material;
	use app\models\Step;
	use yii\web\UploadedFile;
	use yii\imagine\Image;
	use yii\widgets\ActiveForm;
	use yii\data\Pagination;
	
	/**
		* PostController implements the CRUD actions for Post model.
	*/
	class PostController extends Controller
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
			[	'actions'=>['view','latest'],
			'allow'=>true,
			'roles'=>['?'],
			
			]
			],
            ],
			
            'verbs' => [
			'class' => VerbFilter::className(),
			'actions' => [
			'delete' => ['post'],
			'getsubcategory'=>['post'],
			'deletematerial'=>['post'],
			],
            ],
			
			];
		}
		
		public function actions()
		{
			return [
            'inlineupdatematerial' => [
			'class' => 'dosamigos\editable\EditableAction',
			'modelClass'=>'app\models\Material'
            ],
			
			'inlineupdatestep'=>[
			'class' => 'dosamigos\editable\EditableAction',
			'modelClass'=>'app\models\Step',
			//'scenario'=>'update'
			],
			'inlineupdatecomment'=>[
			'class' => 'dosamigos\editable\EditableAction',
			'modelClass'=>'app\models\Comment',
			//'scenario'=>'update'
			],
            
			
			];
		}
		
		/**
			* Lists all Post models.
			* @return mixed
		*/
		public function actionIndex()
		{
			$model = Post::find()->where(['user_id'=>Yii::$app->user->id]);
			$dataProvider =  new ActiveDataProvider([
			'query' => $model,
			'pagination' => [
			'pageSize' => 5,
			],
			'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
			]);
			return $this->render('index', [
            'dataProvider' => $dataProvider,
			]);
		}
		
		/**
			* Displays a single Post model.
			* @param integer $id
			* @return mixed
		*/
		public function actionPreview($id){
			
			$model = Post::findOne([
			'id' => $id,
			'status' => Post::STATUS_DRAFTED,
			]);
			if(!$model == null && $model->user_id == Yii::$app->user->id){
				$modelMaterial = Material::findAll(['post_id'=>$id]);
				$queryStep = Step::find()->where(['post_id' => $id])->orderBy(['order'=>SORT_ASC]);
				$countQuery = clone $queryStep;
				$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>1]);
				$modelStep = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
				
				$related = Post::find()->where(['category_id'=>$model->category_id,'subcategory_id'=>$model->subcategory_id,'status'=>Post::STATUS_PUBLISHED])->orderBy(['RAND()'=>SORT_DESC])->limit(5)->all();
				
				return $this->render('preview', [
				'model' => $model,
				'modelMaterial'=>$modelMaterial,
				'modelStep'=>$modelStep,
				'pages'=>$pages,
				'related'=>$related,
				'popular'=>$this->getPopular()
				
				
				]);
				}else{
				
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
		}
		
		public function actionView($id)
		{	
			
			$model = Post::findOne([
			'id' => $id,
			'status' => Post::STATUS_PUBLISHED,
			]);
			
			if(!$model == null){
				Yii::$app->user->setReturnUrl(Url::to(['view','id'=>$model->id,'slug'=>$model->slug]));
				$modelPostHits = new PostHits();
				$modelPostHits->post_id = $id;
				$modelPostHits->remoteip = Yii::$app->request->userIP;
				$modelPostHits->referrer = Yii::$app->request->referrer;
				$modelPostHits->save();
				$modelMaterial = Material::findAll(['post_id'=>$id]);
				$queryStep = Step::find()->where(['post_id' => $id])->orderBy(['order'=>SORT_ASC]);
				$countQuery = clone $queryStep;
				$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>1]);
				$modelStep = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
				
				$related = Post::find()->where(['category_id'=>$model->category_id,'subcategory_id'=>$model->subcategory_id,'status'=>Post::STATUS_PUBLISHED])->orderBy(['RAND()'=>SORT_DESC])->limit(5)->all();
				
				return $this->render('view', [
				'model' => $model,
				'modelMaterial'=>$modelMaterial,
				'modelStep'=>$modelStep,
				'pages'=>$pages,
				'related'=>$related,
				'popular'=>$this->getPopular()
				
				
				]);
			}
			else{
				
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
		}
		
		public function actionGetrelated(){
			
			$model = Post::find()->limit(5)->all();
			
			return $this->renderAjax('_related',['model'=>$model]);
		}
		
		public function getPopular(){
			
			$rows = (new \yii\db\Query())
			->select('post.title AS title, post.id AS id, post.slug AS slug, post.post_cover AS cover, COUNT(post_hits.post_id) AS hits') 
			->from('post')
			->where('status=:status', [':status' =>Post::STATUS_PUBLISHED])
			->leftJoin('post_hits', 'post_hits.post_id = post.id')
			->groupBy('id')
			->orderBy(['hits'=>SORT_DESC])
			->limit(5)
			->all();
			
			return $rows;
			
			
			
			
			
			
			
		}
		
		
		
		
		
		/**
			* Creates a new Post model.
			* If creation is successful, the browser will be redirected to the 'view' page.
			* @return mixed
		*/
		public function actionCreate()
		{
			$model = new Post();
			
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				//return $this->redirect(['view', 'id' => $model->id]);
				echo Json::encode(array('status'=>'created','url'=>Url::to(['update','id'=>$model->id])));
				} else {
				return $this->renderAjax('_formModal', [
				'model' => $model,
				]);
				
			}
		}
		
		/**
			*Add Material
		*/
		public function actionCreatematerial(){
			$model = new Material();
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				echo Json::encode(array('status'=>'created','id'=>$model->id));
			}
			
			
		}
		public function actionGetmaterial($postid){
			$model = Material::findAll(['post_id'=>$postid]);
			if(!$model == null){
				return $this->renderAjax('_listMaterial',['model'=>$model]);
				}else{
				
				echo '<h4>Belum ada Alat dan Bahan, silahkan tambahkan!</h4>';
			}
		}
		public function actionDeletematerial(){
			if($mid = Yii::$app->request->post('mid')){
				$model = Material::findOne($mid);
				if($model->post->user_id == Yii::$app->user->id && $model->delete()){
					echo Json::encode(array('status'=>'deleted','id'=>$model->id,'postid'=>$model->post_id));
					
				}
				else{
					echo Json::encode(array('status'=>'failed'));
					
				}
				
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		
		/**
			*Add Step
			*
		*/
		
		public function actionCreatestep(){
			$model = new Step();
			
			
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				echo Json::encode(array('status'=>'created','id'=>$model->id));
			}
			
			
		}
		
		public function actionValidatecreatestep(){
			$model = new Step();
			if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
		}
		
		public function actionGetstep($postid){
			$model = Step::find()->where(['post_id'=>$postid])->orderBy(['order'=>SORT_ASC])->all();
			//$modelFotoStep = new EditFotoStep();
			if(!$model == null){
				/**
					Yii::$app->assetManager->bundles = [
					'yii\web\JqueryAsset' => [
					'js'=>[]
					],
					'dosamigos\editable\EditableBootstrapAsset'=>[
					'js'=>[],
					'css'=>[]
					]
					];
				*/
				
				return $this->renderAjax('_listStep',['model'=>$model]);//,'foto'=>$modelFotoStep]);
				}else{
				
				echo '<h4>Belum ada Langkah-langkah, silahkan tambahkan!</h4>';
			}
		}
		
		public function actionUpdatefotostep(){
			if($post = Yii::$app->request->post()){
				
				$model = Step::findOne($post['sid']);
				$model->step_picture = $post['newpic'];
				if($model->post->user_id == Yii::$app->user->id && $model->save()){
					echo Json::encode(array('status'=>'saved','id'=>$model->id,'postid'=>$model->post_id,'steppicture'=>$model->step_picture));
					
				}
				else{
					echo Json::encode(array('status'=>'failed'));
					
				}
				
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
			
		}
		
		public function actionDeletestep(){
			if($sid = Yii::$app->request->post('sid')){
				$model = Step::findOne($sid);
				if($model->post->user_id == Yii::$app->user->id && $model->delete()){
					echo Json::encode(array('status'=>'deleted','id'=>$model->id,'postid'=>$model->post_id));
					
				}
				else{
					echo Json::encode(array('status'=>'failed'));
					
				}
				
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
		}
		
		/**
			* Updates an existing Post model.
			* If update is successful, the browser will be redirected to the 'view' page.
			* @param integer $id
			* @return mixed
		*/
		public function actionUpdate($id)
		{
			$model = $this->findModel($id);
			if($model->user_id == Yii::$app->user->id){
				$modelMaterial = new Material();
				$modelStep = new Step();
				$modelUploadFoto = new UploadFoto();
				$modelUploadFotoStep = new UploadFotoStep();
				$modelEditFotoStep = new EditFotoStep();
				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					//return $this->redirect(['view', 'id' => $model->id]);
					echo Json::encode(array('status'=>'saved','id'=>$model->id));
					} else {
					return $this->render('update', [
					'model' => $model,
					'modelMaterial'=>$modelMaterial,
					'modelStep'=>$modelStep,
					'modelUploadFoto'=>$modelUploadFoto,
					'modelUploadFotoStep'=>$modelUploadFotoStep,
					'modelEditFotoStep'=>$modelEditFotoStep
					]);
				}
				}else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
		}
		
		/**
			* Deletes an existing Post model.
			* If deletion is successful, the browser will be redirected to the 'index' page.
			* @param integer $id
			* @return mixed
		*/
		public function actionDelete($id)
		{
			$model = $this->findModel($id);
			if($model->user_id == Yii::$app->user->id){
				$model->delete();
			}
			
			return $this->redirect(['index']);
		}
		
		/**
			* Finds the Post model based on its primary key value.
			* If the model is not found, a 404 HTTP exception will be thrown.
			* @param integer $id
			* @return Post the loaded model
			* @throws NotFoundHttpException if the model cannot be found
		*/
		protected function findModel($id)
		{
			if (($model = Post::findOne($id)) !== null) {
				return $model;
				} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		
		public function actionGetsubcategory(){
			if($cid = Yii::$app->request->post('cid')){
				$model = Subcategory::findAll(['category_id'=>$cid]);	
				if($model !== null){
					foreach($model as $data){
						echo Html::tag('option',$data->subcategory_name,['value'=>$data->id]);
						
					}
					
				}
			}
			
		}
		public function actionLatest(){
			$provider = new ActiveDataProvider([
			'query' => Post::find()->where(['status'=>Post::STATUS_PUBLISHED]),
			'pagination' => [
			'pageSize' => 3,
			],
			'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
			]);
			
			// get the posts in the current page
			//$posts = $provider->getModels();
			
			
			return $this->renderAjax('_latest',['data'=>$provider]);
			
		}
		
		/**
			* Upload Foto Action
			*
		*/
		public function actionUploadfoto(){
			
			$model = new UploadFoto();
			
			if (Yii::$app->request->isPost) {
				$model->imagefile = UploadedFile::getInstance($model, 'imagefile');
				$filename = time(). '_' .Yii::$app->user->id. '_' . $model->imagefile->baseName  . '.' . $model->imagefile->extension;
				if ($model->imagefile && $model->validate()) {                
					if($model->imagefile->saveAs('image/instruksi/' . $filename)){
						Image::thumbnail('@webroot/image/instruksi/' . $filename, 320, 150)->save(Yii::getAlias('@webroot/image/instruksi/thumb_' . $filename), ['quality' => 50]);
						//Image::text('@webroot/image/instruksi/' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf');
						//Image::text('@webroot/image/instruksi/thumb_' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf');
						
						echo Json::encode(array('status'=>'saved','url'=>Yii::$app->homeUrl . 'image/instruksi/thumb_' . $filename,'filename'=>$filename));
					}
					else{
						echo Json::encode(array('status'=>'notsaved'));
					}
					
				}
			}
			
			//return $this->render('upload', ['model' => $model]);
			
		}
		public function actionUploadfotostep(){
			
			$model = new UploadFotoStep();
			
			if (Yii::$app->request->isPost) {
				$model->imagefile = UploadedFile::getInstance($model, 'imagefile');
				$filename = time(). '_' .Yii::$app->user->id. '_' . $model->imagefile->baseName  . '.' . $model->imagefile->extension;
				if ($model->imagefile && $model->validate()) {                
					if($model->imagefile->saveAs('image/step/' . $filename)){
						Image::thumbnail('@webroot/image/step/' . $filename, 320, 150)->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						//Image::text('@webroot/image/step/' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/' . $filename), ['quality' => 50]);;
						//Image::text('@webroot/image/step/thumb_' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						echo Json::encode(array('status'=>'saved','url'=>Yii::$app->homeUrl . 'image/step/thumb_' . $filename,'filename'=>$filename));
					}
					else{
						echo Json::encode(array('status'=>'notsaved'));
					}
					
				}
			}
			
			//return $this->render('upload', ['model' => $model]);
			
		}
		public function actionEditfotostep(){
			
			$model = new EditFotoStep();
			
			if (Yii::$app->request->isPost) {
				$model->imagefile = UploadedFile::getInstance($model, 'imagefile');
				$filename = time(). '_' .Yii::$app->user->id. '_' . $model->imagefile->baseName  . '.' . $model->imagefile->extension;
				if ($model->imagefile && $model->validate()) {                
					if($model->imagefile->saveAs('image/step/' . $filename)){
						Image::thumbnail('@webroot/image/step/' . $filename, 320, 150)->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						//Image::text('@webroot/image/step/' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/' . $filename), ['quality' => 50]);;
						//Image::text('@webroot/image/step/thumb_' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						echo Json::encode(array('status'=>'saved','url'=>Yii::$app->homeUrl . 'image/step/thumb_' . $filename,'filename'=>$filename));
					}
					else{
						echo Json::encode(array('status'=>'notsaved'));
					}
					
				}
			}
			
			//return $this->render('upload', ['model' => $model]);
			
		}
		public function actionTest(){
			$image = Image::text('@webroot/image/instruksi/thumb_1424182755_6_InstagramCapture_6934f26b-f454-436a-a208-54d743f5147e_jpg.jpg','LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,130])->save(Yii::getAlias('@webroot/image/instruksi/thumb_1424182755_6_InstagramCapture_6934f26b-f454-436a-a208-54d743f5147e_jpg.jpg'), ['quality' => 100]);;
			
			echo var_export($image,true);
		}
	}

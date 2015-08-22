<?php
	
	namespace app\controllers;
	
	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use app\models\LoginForm;
	use app\models\ContactForm;
	use app\models\Auth;
	use app\models\User;
	use app\models\Post;
	use app\models\Category;
	use yii\helpers\Url;
	use yii\imagine\Image;
	use yii\data\ActiveDataProvider;
	use yii\data\Pagination;
	use app\models\Search;
	use app\models\PostInfo;
	use app\models\PostHits;
	use app\models\Subcategory;
	use app\models\UploadFoto;
	use yii\web\UploadedFile;
	use yii\helpers\Json;
	
	class AppController extends Controller
	{	
		//public $successUrl = Url::to(['test']);
		public function behaviors()
		{
			return [
            'access' => [
			'class' => AccessControl::className(),
			'only' => ['logout','about','setting','editavatar'],
			'rules' => [
			[
			'actions' => ['logout','about','setting','editavatar'],
			'allow' => true,
			'roles' => ['@'],
			],
			],
            ],
            'verbs' => [
			'class' => VerbFilter::className(),
			'actions' => [
			'logout' => ['post'],
			],
            ],
			];
		}
		
		public function actions()
		{
			return [
            'error' => [
			'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
			'class' => 'yii\captcha\CaptchaAction',
			//'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
			'auth' => [
			'class' => 'yii\authclient\AuthAction',
			'successCallback' => [$this, 'onAuthSuccess'],
			'successUrl' => Yii::$app->user->getReturnUrl(),
            ],
			'inlineupdateuser'=>[
			'class' => 'dosamigos\editable\EditableAction',
			'modelClass'=>'app\models\User',
			'scenario'=>'update'
			],
			
			];
		}
		
		public function actionIndex()
		{
			return $this->render('index');
		}
		
		public function actionSearch($k)
		{
			
			$search = Search::find()->where(['keyword'=>$k])->one();
			if(!$search == null){
				$search->frequency = $search->frequency + 1;
				$search->save();
			}
			else{
				$search = new Search();
				$search->keyword = $k;
				$search->frequency = 1;
				$search->save();
			}
			$count = Post::find()->where(['and', 'status=:status',['or',['like','title',$k],['like','description',$k]]])->params([':status' => Post::STATUS_PUBLISHED])->orderBy(['created_at'=>SORT_DESC])->count();
			
			return $this->render('result',['count'=>$count,'keyword'=>$k]);
			//print_r($model);
		}
		
		public function actionSearchbykeyword($k){
			
			$queryStep = Post::find()->where(['and', 'status=:status',['or',['like','title',$k],['like','description',$k]]])->params([':status' => Post::STATUS_PUBLISHED])->orderBy(['created_at'=>SORT_DESC]);
			$countQuery = clone $queryStep;
			$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>3]);
			$model = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
			
			return $this->renderAjax('_result',['model'=>$model,'pages'=>$pages,'keyword'=>$k]);
			
			
		}
		
		public function actionAuthordd($u){
			$queryStep = Post::find()->where(['and', 'status=:status',['or',['like','title',$k],['like','description',$k]]])->params([':status' => Post::STATUS_PUBLISHED])->orderBy(['created_at'=>SORT_DESC]);
			$countQuery = clone $queryStep;
			$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>3]);
			$model = $queryStep->offset($pages->offset)->limit($pages->limit)->all();
			
			return $this->render('result',['model'=>$model,'pages'=>$pages,'keyword'=>$k]);
			
		}
		
		public function actionPostbyauthor($u){
			$query = (new \yii\db\Query())
			->select('post.id AS id, post.title AS title, post.description AS description,  post.slug AS slug, post.post_cover AS cover, user.username AS username , user.displayName AS displayName, post.status AS status') 
			->from('post')
			->innerJoin('user', 'user.id = post.user_id')
			->where('post.status=:status', [':status' =>Post::STATUS_PUBLISHED])
			->andWhere('user.username=:username',[':username'=>$u])
			->groupBy('id')
			->orderBy(['post.created_at'=>SORT_DESC]);
			$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
			'pageSize' => 3,
			],
			]);
			
			//print_r($provider);
			//echo '<pre>';
			//echo var_export($provider->getModels(),true);
			//echo '</pre>';
			
			return $this->renderAjax('_userResult',['provider'=>$provider,'u'=>$u]);
			
		}
		
		public function actionAuthor($u){
			
			$query = (new \yii\db\Query())
			->select('post.id AS id, post.title AS title, post.description AS description,  post.slug AS slug, post.post_cover AS cover, user.username AS username , user.displayName AS displayName, post.status AS status') 
			->from('post')
			->innerJoin('user', 'user.id = post.user_id')
			->where('post.status=:status', [':status' =>Post::STATUS_PUBLISHED])
			->andWhere('user.username=:username',[':username'=>$u])
			->groupBy('id')
			->orderBy(['post.created_at'=>SORT_DESC])
			->count();
			
			return $this->render('userResult',['u'=>$u,'count'=>$query]);
		}
		
		public function getPostInfo($postid,$type){
			switch($type){
				case 'favorit':
				$model = PostInfo::find()->where(['post_id'=>$postid,'type'=>PostInfo::FAVORIT])->count();
				return $model;
				break;
				case 'made':
				$model = PostInfo::find()->where(['post_id'=>$postid,'type'=>PostInfo::MADE])->count();
				return $model;
				break;
			}
			
		}
		public function getPostHits($postid){
			$model = PostHits::find()->where(['post_id'=>$postid])->count();
			return $model;
			
		}
		public function actionPostbycategory($cat,$sub= null){
			if($sub == '')
			$sub = null;
			
			$query = (new \yii\db\Query())
			->select('post.id AS id, post.title AS title, post.description AS description,  post.slug AS slug, post.post_cover AS cover, user.username AS username , user.displayName AS displayName, post.status AS status , category.category_name AS category, category.category_code AS category_code, subcategory.subcategory_name AS subcagetory, subcategory.subcategory_code AS subcategory_code') 
			->from('post')
			->innerJoin('category', 'category.id = post.category_id')
			->innerJoin('user', 'user.id = post.user_id')
			->innerJoin('subcategory','subcategory.id = post.subcategory_id')
			->where('post.status=:status', [':status' =>Post::STATUS_PUBLISHED])
			->andWhere('category.category_code=:cat',[':cat'=>$cat]);
			if($sub !== null)
			$query->andWhere('subcategory.subcategory_code=:sub',[':sub'=>$sub]);
			$query->groupBy('id')
			->orderBy(['post.created_at'=>SORT_DESC]);	
			$provider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
			'pageSize' => 3,
			],
			]);
			
			//print_r($provider);
			//echo '<pre>';
			//echo var_export($provider->getModels(),true);
			//echo '</pre>';
			return $this->renderAjax('_categoryResult',['provider'=>$provider,'cat'=>$cat,'sub'=>$sub]);
			
			
		}
		
		public function getCategoryName($cat){
			
			$model = Category::find()->where(['category_code'=>$cat])->one();
			if(!$model == null){
				
				return $model->category_name;
			}
			else{
				return $cat;
			}
			
		}
		public function getSubcategoryName($sub){
			
			$model = Subcategory::find()->where(['subcategory_code'=>$sub])->one();
			if(!$model == null){
				
				return $model->subcategory_name;
			}
			else{
				return $sub;
			}
			
		}
		
		public function actionCategory($cat,$sub = null){
			if($sub == '')
			$sub = null;
			
			$query = (new \yii\db\Query())
			->select('post.id AS id, post.title AS title, post.description AS description,  post.slug AS slug, post.post_cover AS cover, user.username AS user , user.displayName AS displayName, post.status AS status , category.category_name AS category, category.category_code AS category_code, subcategory.subcategory_name AS subcagetory, subcategory.subcategory_code AS subcategory_code') 
			->from('post')
			->innerJoin('category', 'category.id = post.category_id')
			->innerJoin('user', 'user.id = post.user_id')
			->innerJoin('subcategory','subcategory.id = post.subcategory_id')
			->where('post.status=:status', [':status' =>Post::STATUS_PUBLISHED])
			->andWhere('category.category_code=:cat',[':cat'=>$cat]);
			if($sub !== null)
			$query->andWhere('subcategory.subcategory_code=:sub',[':sub'=>$sub]);
			$count = $query->groupBy('id')
			->orderBy(['post.created_at'=>SORT_DESC])
			->count();	
			
			return $this->render('categoryResult',['cat'=>$cat, 'sub'=>$sub, 'count'=>$count]);
			
			
		}
		
		public function actionSetting(){
			$model = User::findOne(Yii::$app->user->id);
			if(!$model == null){
				$modelUploadFoto = new UploadFoto();
				return $this->render('profilesetting',['user'=>$model,'modelUploadFoto'=>$modelUploadFoto]);
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
				
			}
		}
		
		public function actionEditavatar(){
			$model = new UploadFoto();
			
			if (Yii::$app->request->isPost) {
				$model->avatar = UploadedFile::getInstance($model, 'avatar');
				$filename = time(). '_' .Yii::$app->user->id. '_' . $model->avatar->baseName  . '.' . $model->avatar->extension;
				if ($model->avatar && $model->validate()) {                
					if($model->avatar->saveAs('image/avatar/' . $filename)){
						//Image::thumbnail('@webroot/image/avatar/' . $filename, 320, 150)->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						//Image::text('@webroot/image/step/' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/' . $filename), ['quality' => 50]);;
						//Image::text('@webroot/image/step/thumb_' . $filename,'LakukanSendiri.com','@webroot/font/OpenSans-Semibold.ttf',[5,5],['color'=>'fff','size'=>12,'angle'=>0])->save(Yii::getAlias('@webroot/image/step/thumb_' . $filename), ['quality' => 50]);
						echo Json::encode(array('status'=>'saved','url'=>Yii::$app->homeUrl . 'image/avatar/' . $filename,'filename'=>$filename));
					}
					else{
						echo Json::encode(array('status'=>'notsaved'));
					}
					
				}
			}
			
		}
		public function actionUpdateavatar(){
			if($post = Yii::$app->request->post()){
				
				$model = User::findOne($post['userid']);
				$model->avatar = $post['newpic'];
				if($model->id == Yii::$app->user->id && $model->save()){
					echo Json::encode(array('status'=>'saved','id'=>$model->id,'avatar'=>$model->avatar));
					
				}
				else{
					echo Json::encode(array('status'=>'failed'));
					
				}
				
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
			
		}
		
		public function actionUser($u){
			$model = User::findOne(['username'=>$u]);
			if(!$model == null){
				return $this->render('user',['user'=>$model]);
				}else{
				throw new NotFoundHttpException('The requested page does not exist.');
				
			}
		}
		
		public function actionLogin()
		{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
			}
			return $this->render('login');
			/*
				$model = new LoginForm();
				if ($model->load(Yii::$app->request->post()) && $model->login()) {
				return $this->goBack();
				} else {
				return $this->render('login', [
				'model' => $model,
				]);
				}
			*/
		}
		
		public function actionLogout()
		{
			Yii::$app->user->logout();
			
			return $this->goHome();
		}
		
		public function actionContact()
		{
			$model = new ContactForm();
			if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('contactFormSubmitted');
				
				return $this->refresh();
				} else {
				return $this->render('contact', [
				'model' => $model,
				]);
			}
		}
		
		public function actionAbout()
		{
			return $this->render('about');
		}
		public function actionTest()
		{/*
			$userid = Yii::$app->user->id;
			$imageid = Yii::$app->security->generateRandomString(6);
			$savepath = "@webroot/image/{$userid}/";
			$filename = "thumb_{$userid}_{$imageid}.jpg";
			if (!file_exists(Yii::getAlias($savepath)))
			{
			mkdir(Yii::getAlias($savepath), 0777);
			}
			Image::thumbnail('@webroot/image/bpu.jpg', 120, 120)
			->save(Yii::getAlias($savepath.$filename), ['quality' => 50]);
		*/
		//$userHost = Yii::$app->request->userHost;
		//$userIP = Yii::$app->request->userIP;
		//echo $userHost.' '.$userIP;
		$session = Yii::$app->session;
		echo '<pre>'.var_export($session['attributes']).'</pre>';
		//echo  Url::previous();
		//print_r(Yii::$app->user->identity);
		}
		public function onAuthSuccess($client)
		{
			$attributes = $client->getUserAttributes();
			$session = Yii::$app->session;
			$session['attributes']=$attributes;
			//$this->successUrl = Url::to(['test']);
			/** @var Auth $auth */
			
			switch($client->getId()){
				case 'facebook':
				$avatar = "https://graph.facebook.com/{$attributes['id']}/picture?width=150&height=150";
				$username = strtolower($attributes['first_name'].$attributes['last_name']);
				$email = $attributes['email'];
				$displayName = $attributes['name'];
				break;
				case 'google':
				$avatar = str_replace('50','150',$attributes['image']['url']);
				$username = strtolower($attributes['name']['givenName'].$attributes['name']['familyName']);
				$email = $attributes['emails'][0]['value'];
				$displayName = $attributes['displayName'];
				break;
				default:
			}
			$auth = Auth::find()->where([
			'source' => $client->getId(),
			'source_id' => $attributes['id'],
			])->one();
			
			//print_r($attributes);
			
			
			if (Yii::$app->user->isGuest) {
				if ($auth) { // login
					$user = $auth->user;
					Yii::$app->user->login($user);
					} else { // signup
					if (User::find()->where(['username' => $username])->exists())
					{
						$username .= "_{$attributes['id']}";
					}
					if (User::find()->where(['email' => $email])->exists() ) {
						Yii::$app->getSession()->setFlash('error',"email exists");
						} else {
						$password = Yii::$app->security->generateRandomString(6);
						$user = new User([
						'username' => $username,
						'email' => $email,
						'avatar'=> $avatar,
						'password' => $password,
						'displayName'=>$displayName,
						]);
						$user->generateAuthKey();
						$user->generatePasswordResetToken();
						$transaction = $user->getDb()->beginTransaction();
						if ($user->save()) {
							$auth = new Auth([
							'user_id' => $user->id,
							'source' => $client->getId(),
							'source_id' => (string)$attributes['id'],
							]);
							if ($auth->save()) {
								$transaction->commit();
								Yii::$app->user->login($user);
								} else {
								print_r($auth->getErrors());
							}
							} else {
							print_r($user->getErrors());
						}
					}
				}
				} else { // user already logged in
				if (!$auth) { // add auth provider
					$auth = new Auth([
					'user_id' => Yii::$app->user->id,
					'source' => $client->getId(),
					'source_id' => (string)$attributes['id'],
					]);
					$auth->save();
				}
			}
			
		}
		public function actionGetcategory(){
			
			$model = Category::find()->All();
			return $this->renderAjax('category',['model'=>$model]);
			//print_r($model);
		}
	}

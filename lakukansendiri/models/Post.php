<?php
	
	namespace app\models;
	
	use Yii;
	use yii\behaviors\TimestampBehavior;
	use yii\behaviors\AttributeBehavior;
	use yii\behaviors\SluggableBehavior;
	use yii\db\ActiveRecord;

	/**
		* This is the model class for table "post".
		*
		* @property integer $id
		* @property integer $user_id
		* @property string $title
		* @property string $description
		* @property string $post_cover
		* @property integer $category_id
		* @property integer $subcategory_id
		* @property integer $created_at
		* @property integer $updated_at
		*
		* @property Comment[] $comments
		* @property Material[] $materials
		* @property Category $category
		* @property Subcategory $subcategory
		* @property User $user
		* @property PostHits[] $postHits
		* @property PostInfo[] $postInfos
		* @property Step[] $steps
	*/
	class Post extends ActiveRecord
	{
		
		const STATUS_DRAFTED = 0;
		const STATUS_PUBLISHED = 10;
		
		/**
			* @inheritdoc
		*/
		public static function tableName()
		{
			return 'post';
		}
		
		/**
			* 
		*/
		public function behaviors()
		{
			return [
			[
            'class'=>TimestampBehavior::className(),
			],
			[
			'class'=>AttributeBehavior::className(),
			'attributes'=>[
			ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
			],
			'value'=>function($event){
				return Yii::$app->user->id;
			}
			
			],
			[
			'class' => SluggableBehavior::className(),
            'attribute' => 'title',
			
			]
			];
		}
		
		/**
			* @inheritdoc
		*/
		public function rules()
		{
			return [
            [[ 'title','description', 'category_id', 'subcategory_id'], 'required'],
            [['user_id', 'category_id', 'subcategory_id', 'created_at', 'updated_at','status'], 'integer'],
            [['description'], 'string','max'=>350,'min'=>140],
			[['title', 'description'], 'filter', 'filter' => function($value) {
				return strip_tags($value);
			}],
            [['title'], 'string', 'max' => 255]
			];
		}
		
		/**
			* @inheritdoc
		*/
		public function attributeLabels()
		{
			return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Judul',
            'description' => 'Deskripsi Singkat',
            'post_cover' => 'Image Cover',
			'category_id' => 'Kategori',
			'subcategory_id' => 'Sub Kategori',
			'created_at' => 'Dibuat pada',
			'updated_at' => 'Diperbarui pada',
			];
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getComments()
		{
			return $this->hasMany(Comment::className(), ['post_id' => 'id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getMaterials()
		{
			return $this->hasMany(Material::className(), ['post_id' => 'id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getCategory()
		{
			return $this->hasOne(Category::className(), ['id' => 'category_id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getSubcategory()
		{
			return $this->hasOne(Subcategory::className(), ['id' => 'subcategory_id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getUser()
		{
			return $this->hasOne(User::className(), ['id' => 'user_id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getPostHits()
		{
			return $this->hasMany(PostHits::className(), ['post_id' => 'id']);
		}
		public function getPostHitsCount(){
			return $this->hasMany(PostHits::className(), ['post_id' => 'id'])->count();
			
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getPostInfos()
		{
			return $this->hasMany(PostInfo::className(), ['post_id' => 'id']);
		}
		public function getPostFavorit(){
			
			return 	$this->hasMany(PostInfo::className(), ['post_id' => 'id'])->where(['type'=>\app\models\PostInfo::FAVORIT])->count();
		}
		
		public function getPostMade(){
			return 	$this->hasMany(PostInfo::className(), ['post_id' => 'id'])->where(['type'=>\app\models\PostInfo::MADE])->count();
		}
		
	
		
	
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getSteps()
		{
			return $this->hasMany(Step::className(), ['post_id' => 'id']);
			}
		
		public function getIndonesiancalender(){
			$date = Yii::$app->formatter->asDate($this->created_at,'dd');
			$month = Yii::$app->formatter->asDate($this->created_at,'MM');
			$year = Yii::$app->formatter->asDate($this->created_at,'yyyy');
			$time = Yii::$app->formatter->asTime($this->created_at,'HH:mm');
			$monthname = '';
			switch($month){
				case '01':
				$monthname = 'Januari';
				break;
				case '02':
				$monthname = 'Februari';
				break;
				case '03':
				$monthname = 'Maret';
				break;
				case '04':
				$monthname = 'April';
				break;
				case '05':
				$monthname = 'Mei';
				break;
				case '06':
				$monthname = 'Juni';
				break;
				case '07':
				$monthname = 'Juli';
				break;
				case '08':
				return 'Agustus';
				break;
				case '09':
				$monthname = 'September';
				break;
				case '10':
				$monthname = 'Oktober';
				break;
				case '11':
				$monthname = 'November';
				break;
				case '12':
				$monthname = 'Desember';
				break;
				
				
				
			}
			
			return 'Tanggal ' . $date .' '. $monthname . ' ' . $year ;//.  ' pukul ' . $time;
			
			
		}
	}

<?php
	
	namespace app\models;
	
	use Yii;
	use yii\db\ActiveRecord;
	use yii\behaviors\TimestampBehavior;
	use yii\behaviors\AttributeBehavior;
	use yii\data\Pagination;
	/**
		* This is the model class for table "comment".
		*
		* @property integer $id
		* @property integer $post_id
		* @property integer $user_id
		* @property string $comment_content
		* @property integer $comment_parent
		* @property integer $created_at
		* @property integer $updated_at
		*
		* @property Post $post
		* @property User $user
	*/
	class Comment extends ActiveRecord
	{
		/**
			* @inheritdoc
		*/
		public static function tableName()
		{
			return 'comment';
		}
		
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
			
			]
			
			];
		}
		
		/**
			* @inheritdoc
		*/
		public function rules()
		{
			return [
            [['post_id', 'comment_content'], 'required'],
            [['post_id', 'user_id', 'comment_parent', 'created_at', 'updated_at'], 'integer'],
            [['comment_content'], 'string'],
			[['comment_content'], 'filter', 'filter' => function($value) {
				return strip_tags($value);
			}],
			];
		}
		
		/**
			* @inheritdoc
		*/
		public function attributeLabels()
		{
			return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'comment_content' => 'Komentar',
            'comment_parent' => 'Comment Parent',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
			];
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getPost()
		{
			return $this->hasOne(Post::className(), ['id' => 'post_id']);
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getUser()
		{
			return $this->hasOne(User::className(), ['id' => 'user_id']);
		}
		
		public function getReply(){
			$query =$this->find()->where(['comment_parent'=>$this->id])->orderBy(['created_at'=>SORT_DESC]);
			$countQuery = clone $query;
			$pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>3]);
			$model = $query->offset($pages->offset)->limit($pages->limit)->all();
			
			return $model;
		}
		public function getReplypagination(){
			$query = $this->find()->where(['comment_parent'=>$this->id])->orderBy(['created_at'=>SORT_DESC]);
			$pages = new Pagination(['totalCount' => $query->count(),'pageSize'=>3]);
			return $pages;
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
			
			return 'Tanggal ' . $date .' '. $monthname . ' ' . $year .' pukul ' . $time;
			
			
		}
		
	}

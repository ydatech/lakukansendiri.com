<?php
	
	namespace app\models;
	
	use Yii;
	use yii\behaviors\TimestampBehavior;
	//use yii\behaviors\AttributeBehavior;
	use yii\db\ActiveRecord;
	/**
		* This is the model class for table "post_info".
		*
		* @property integer $id
		* @property integer $post_id
		* @property integer $user_id
		* @property integer $type
		* @property integer $created_at
		*
		* @property Post $post
		* @property User $user
	*/
	class PostInfo extends ActiveRecord
	{
		const FAVORIT = 10;
		const MADE = 20;
		/**
			* @inheritdoc
		*/
		public static function tableName()
		{
			return 'post_info';
		}
		
		public function behaviors()
		{
			return [
			[
            
			'class' => TimestampBehavior::className(),
			'attributes' => [
			ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
			],
			],
			
			];
		}
		
		/**
			* @inheritdoc
		*/
		public function rules()
		{
			return [
            [['post_id','user_id', 'type'], 'required'],
            [['post_id', 'user_id', 'type', 'created_at'], 'integer'],
			['user_id', 'unique', 'targetAttribute' => ['user_id', 'post_id','type'],'message' => 'Anda sudah melakukan ini.'],
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
            'type' => 'Type',
            'created_at' => 'Created At',
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
	}

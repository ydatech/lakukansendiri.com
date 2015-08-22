<?php
	
	namespace app\models;
	
	use Yii;
	
	/**
		* This is the model class for table "step".
		*
		* @property integer $id
		* @property integer $post_id
		* @property string $step_title
		* @property string $step_description
		* @property string $step_picture
		*
		* @property Post $post
	*/
	class Step extends \yii\db\ActiveRecord
	{
		/**
			* @inheritdoc
		*/
		public static function tableName()
		{
			return 'step';
		}
		
		/**
			* @inheritdoc
		*/
		public function rules()
		{
			return [
            [['post_id', 'step_title', 'step_description','order'], 'required'],
            [['post_id','order'], 'integer'],
			['order', 'unique', 'targetAttribute' => ['order', 'post_id'],'message' => 'Urutan Langkah tidak boleh sama dengan langkah yang lain.'],

            [['step_description','step_video'], 'string'],
			[['step_title', 'step_description'], 'filter', 'filter' => function($value) {
				return strip_tags($value);
			}],
			[['step_video'],'url'],
			[['step_video'],'filter','filter'=>function($url){
				if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
					return $id[1];
					} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
					return $id[1];
					} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
					return $id[1];
					} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
					return $id[1];
				}
				else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
					return $id[1];
					} else {   
					return null;// not an youtube video
				}
				
			}],
            [['step_title','step_picture'], 'string', 'max' => 255]
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
            'step_title' => 'Judul',
            'step_description' => 'Deskripsi',
            'step_picture' => 'Foto',
			'step_video'=>'URL Video',
			'order'=>'Urutan Langkah'
			];
		}
		
		/**
			* @return \yii\db\ActiveQuery
		*/
		public function getPost()
		{
			return $this->hasOne(Post::className(), ['id' => 'post_id']);
		}
	}

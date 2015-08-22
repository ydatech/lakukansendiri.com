<?php
	namespace app\models;
	
	use Yii;	
	use yii\base\Model;
	use yii\web\UploadedFile;
	
	class UploadFoto extends Model
	{
		/**
			* @var UploadedFile|Null file attribute
		*/
		public $imagefile;
		public $avatar;
		
		/**
			* @return array the validation rules.
		*/
		public function rules()
		{
			return [
            //[['imagefile'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',], // <--- here!
			//[['imagefile'],'required'],
			[['imagefile'], 'image', 'extensions' => 'png, jpg', 'maxSize'=>2000000,
			'minWidth' => 320,'minHeight' => 150,
			],
			[['avatar'], 'image', 'extensions' => 'png, jpg', 'maxSize'=>2000000,
			'minWidth' => 150,'minHeight' => 150,'maxWidth'=>160,'maxHeight'=>160
			],
			
			];
		}
		/**
			* @inheritdoc
		*/
		public function attributeLabels()
		{
			return [
            'imagefile' => 'Upload Foto',
            
			];
		}
	}			
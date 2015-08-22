<?php
	namespace app\models;
	
	use Yii;	
	use yii\base\Model;
	use yii\web\UploadedFile;
	
	class UploadFotoStep extends Model
	{
		/**
			* @var UploadedFile|Null file attribute
		*/
		public $imagefile;
		
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
			]
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
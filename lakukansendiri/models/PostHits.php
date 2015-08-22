<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post_hits".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $remoteip
 * @property string $referrer
 * @property integer $counter
 * @property integer $created_at
 *
 * @property Post $post
 */
class PostHits extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_hits';
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
			[
			'class'=>AttributeBehavior::className(),
			'attributes'=>[
			ActiveRecord::EVENT_BEFORE_INSERT => 'counter',
			],
			'value'=>function($event){
				return 1;
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
            [['post_id'], 'required'],
            [['post_id', 'counter', 'created_at'], 'integer'],
            [['remoteip'], 'string', 'max' => 30],
            [['referrer'], 'string', 'max' => 255]
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
            'remoteip' => 'Remoteip',
            'referrer' => 'Referrer',
            'counter' => 'Counter',
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
}

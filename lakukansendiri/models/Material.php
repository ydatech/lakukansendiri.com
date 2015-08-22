<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "material".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string $material_name
 * @property integer $material_amount
 * @property string $material_unit
 *
 * @property Post $post
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'material_name', 'material_amount', 'material_unit'], 'required'],
            [['post_id', 'material_amount'], 'integer'],
            [['material_name', 'material_unit'], 'string', 'max' => 255]
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
            'material_name' => 'Nama Alat atau Bahan',
            'material_amount' => 'Jumlah',
            'material_unit' => 'Satuan',
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

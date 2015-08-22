<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		  [['username', 'email'], 'required','on'=>'update'],
		  ['email', 'email','on'=>'update'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			['username', 'unique','on'=>'update'],
			['email', 'unique','on'=>'update'],
			
			
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	public function getPost(){
		return $this->hasMany(Post::className(), ['user_id' => 'id'])->where(['status'=>\app\models\Post::STATUS_PUBLISHED]);
		
		}
	public function getLatestPost(){
		
		return $this->hasMany(Post::className(), ['user_id' => 'id'])->where(['status'=>\app\models\Post::STATUS_PUBLISHED])->orderBy(['created_at'=>SORT_DESC])->limit(3);
		}
	public function getFavorit(){
		return $this->hasMany(PostInfo::className(), ['user_id' => 'id'])->where(['type'=>\app\models\PostInfo::FAVORIT]);
		}
		
	public function getMade(){
		return $this->hasMany(PostInfo::className(), ['user_id' => 'id'])->where(['type'=>\app\models\PostInfo::MADE]);
		
		}
	public function getComment(){
		return $this->hasMany(Comment::className(), ['user_id' => 'id']);
		
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
			
			return $date .' '. $monthname . ' ' . $year ;//.  ' pukul ' . $time;
			
			
		}
}

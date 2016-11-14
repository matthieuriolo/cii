<?php

namespace app\modules\cii\models\auth;

use Yii;

use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;

use app\modules\cii\models\extension\Language;
use app\modules\cii\models\extension\Layout;

/**
 * This is the model class for table "{{%Cii_User}}".
 *
 * @property integer $id
 * @property string $created
 * @property string $activated
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $enabled
 * @property integer $language_id
 * @property integer $layout_id
 * @property string $reset_token
 * @property string $activation_token
 *
 * @property CiiGroupMembers[] $ciiGroupMembers
 * @property CiiLanguage $language
 * @property CiiLayout $layout */
class User extends ActiveRecord implements IdentityInterface {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Cii_User}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'enabled'], 'required'],
            [['language_id', 'layout_id'], 'integer'],
            [['enabled'], 'boolean'],
            [['username', 'email'], 'string', 'max' => 255],
            [['password', 'token'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['email'], 'email'],
            
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => Layout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created' => Yii::p('cii', 'Created'),
            'activated' => Yii::p('cii', 'Activated'),
            'username' => Yii::p('cii', 'Username'),
            'email' => Yii::p('cii', 'Email'),
            'password' => Yii::p('cii', 'Password'),
            'enabled' => Yii::p('cii', 'Enabled'),
            'language_id' => Yii::p('cii', 'Default language'),
            'layout_id' => Yii::p('cii', 'Default Layout'),
            'reset_token' => Yii::p('cii', 'Activation Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupMembers() {
        return $this->hasMany(GroupMembers::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage() {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayout() {
        return $this->hasOne(Layout::className(), ['id' => 'layout_id']);
    }


    public function recoverPassword() {
        $password = Yii::$app->getSecurity()->generateRandomString(8);
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $this->token = null;
        if(!$this->save() || !Yii::$app->cii->mail(
            'app\modules\cii\mails\recovery',
            $this->email,
            [
                'user' => $this,
                'password' => $password,
            ]
        )) {
            throw new UserException('Could not sent recovery mail');
        }

        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getId() {
        return $this->getPrimaryKey();
    }

    public static function findIdentity($id) {
        return static::findOne([
            'id' => $id,
            'enabled' => true,
            'token' => null
        ]);
    }

    public function getAuthKey() {
        return Yii::$app->getSecurity()->generatePasswordHash($this->getId());
    }
    
    public function validateAuthKey($authKey) {
        return Yii::$app->getSecurity()->validatePassword((string)$this->getId(), $authKey);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => 'created'
            ]
        ];
    }
}

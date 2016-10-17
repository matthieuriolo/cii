<?php

namespace app\modules\cii\models;

use Yii;
use cii\base\OrderModelInterface;

/**
 * This is the model class for table "Core_ContentVisibilities".
 *
 * @property integer $id
 * @property integer $content_id
 * @property integer $route_id
 * @property integer $language_id
 * @property string $position
 *
 * @property CoreLanguage $language
 * @property CoreRoute $route
 * @property CoreContent $content
 */
class ContentVisibilities extends \yii\db\ActiveRecord implements OrderModelInterface {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%Cii_ContentVisibilities}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content_id'], 'required'],
            [['content_id', 'route_id', 'language_id', 'ordering'], 'integer'],
            [['position'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' =>Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['content_id' => 'id']],
        ];
    }

    public function beforeSave($insert) {
        if($this->ordering === null) {
            $this->ordering = 1 + (int)$this::find()
                ->where([
                    'position' => $this->position
                ])
                ->max('ordering')
            ;
        }
        
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'content_id' => Yii::p('cii', 'Content'),
            'route_id' => Yii::p('cii', 'Route'),
            'language_id' => Yii::p('cii', 'Language'),
            'position' => Yii::p('cii', 'Position'),
        ];
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
    public function getRoute() {
        return $this->hasOne(Route::className(), ['id' => 'route_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent() {
        return $this->hasOne(Content::className(), ['id' => 'content_id']);
    }


    public function orderUp() {
        if($model = $this->previous()) {
           return $this->switchOrder($model);
        }

        return true;
    }

    public function orderDown() {
        if($model = $this->next()) {
           return $this->switchOrder($model);
        }

        return true;
    }

    public function previous() {
        return $this::find()
            ->where([
                'position' => $this->position,
            ])
            ->andWhere(['<', 'ordering', $this->ordering])
            ->one()
        ;
    }

    public function next() {
        return $this::find()
            ->where([
                'position' => $this->position,
            ])
            ->andWhere(['>', 'ordering', $this->ordering])
            ->one()
        ;
    }

    protected function switchOrder($model) {
        $order = $model->ordering;
        $model->ordering = $this->ordering;
        $this->ordering = $order;
        return $this->save() && $model->save();
    }
}

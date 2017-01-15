<?php

namespace app\modules\cii\models\common;

use Yii;
use yii\db\ActiveRecord;

use app\modules\cii\models\extension\Language;
use cii\helpers\Plotter;

class Route extends ActiveRecord {
    public $type;
    
    public static function tableName() {
        return '{{%Cii_Abstract_Route}}';
    }

    public function rules() {
        return [
            [['slug', 'enabled', 'type'], 'required'],
            [['slug', 'title'], 'string', 'max' => 255],

            [['parent_id', 'language_id'], 'integer'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['slug'], 'valideSlug'],
            [['enabled'], 'boolean'],

            [['hits'], 'integer'],

            [['type'], 'in', 'range' => Yii::$app->cii->route->getTypeValues()]
        ];
    }
    
    public function valideSlug($attribute, $params) {
        $val = $this->$attribute;
        if(preg_match('/^([A-Z]|[a-z]|\d|\.|-|_)+$/', $val)) {
            return true;
        }

        $this->addError($attribute, $this->getAttributeLabel($attribute) . ' can only consist of A-Z, 0-9, -, . or _');
        return false;
    }

    public function afterFind() {
        $this->type = $this->classname->path;
        return parent::afterFind();
    }

    /* belongs to an own behaviour */
    public function getParentChain() {
        $tks = [$this];
        $node = $this;
        while($node = $node->parent) {
            $tks[] = $node;
        }

        return array_reverse($tks);
    }

    /* belongs to an own behaviour */
    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id'])->inverseOf('children');
    }

    public function getFullSlug() {
        $tks = $this->getParentChain();
        return array_map(function($route) {
            return $route->slug;
        }, $tks);
    }

    public function getBreadcrumbs() {
        $arrs = $this->getFullSlug();
        return implode('/', $arrs);
    }

    public function getChildren() {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->inverseOf('parent');
    }

    public function hasChildren() {
        return self::findOne(['parent_id' => $this->id]) ? true : false;
    }

    public function getLanguage() {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function getAccesses() {
        return $this->hasMany(CountAccess::className(), ['route_id' => 'id']);
    }

    public function attributeLabels() {
        return [
            'slug' => Yii::p('cii', 'Address path'),
            'title' => Yii::p('cii', 'Website title'),
            'enabled' => Yii::p('cii', 'Enabled'),
            'classname' => Yii::p('cii', 'Type'),
            'language_id' => Yii::p('cii', 'Language'),
            'parent_id' => Yii::p('cii', 'Parent'),
            'breadcrumb' => Yii::p('cii', 'Breadcrumb'),

            'hits' => Yii::p('cii', 'Total accesses'),
            'averageHits' => Yii::p('cii', 'Average accesses'),
            'dailyHits' => Yii::p('cii', 'Daily accesses'),
            'weeklyHits' => Yii::p('cii', 'Weekly accesses'),
            'monthlyHits' => Yii::p('cii', 'Monthly accesses'),
            'yearlyHits' => Yii::p('cii', 'Yearly accesses'),
        ];
    }




    public function getDailyHits() {
        return $this->countHits('1D');
    }

    public function getWeeklyHits() {
        return $this->countHits('7D');
    }

    public function getMonthlyHits() {
        return $this->countHits('1M');
    }

    public function getYearlyHits() {
        return $this->countHits('1Y');
    }

    public function getAverageHits() {
        return $this->avgHits();
    }

    protected function avgHits($sub = null) {
        return $this->countHits($sub, 'average');
    }

    protected function countHits($sub = null, $func = 'sum') {
        $query = CountAccess::find()->where(['route_id' => $this->id]);
        if(!is_null($sub)) {
            $date = new \DateTime("now", new \DateTimeZone('UTC'));
            $date->sub(new \DateInterval('P'.$sub));
            $query->andWhere('created >= :date', [':date' => $date->format('Y-m-d')]);
        }

        return $query->$func('hits') ?: 0;
    }

    protected static function countViewStats($column, $range, $steps) {
        $cache = Yii::$app->cache;
        $cacheKey = get_called_class() . '_' . $column . '_' . $range . '_' . $steps;
        
        if($data = $cache->get($cacheKey)) {
            return $data;
        }

        $data = Plotter::plotByDatetime(
            self::find()
                ->joinWith('accesses as countaccess')
            , 'countaccess.created', $range, $steps, 'sum', ['countaccess.' . $column]
        );
        
        $cache->set($cacheKey, $data, 60 * 60);

        return $data;
    }

    public static function weeklyViewStats() {
        return self::countViewStats('hits' , 'D', 7);
    }

    public static function monthlyViewStats() {
        return self::countViewStats('hits' , 'D', 30);
    }

    public static function yearlyViewStats() {
        return self::countViewStats('hits' , 'M', 12);
    }

    public static function weeklyBounceRateStats() {
        return self::countViewStats('bounceHits' , 'D', 7);
    }

    public static function monthlyBounceRateStats() {
        return self::countViewStats('bounceHits' , 'D', 30);
    }

    public static function yearlyBounceRateStats() {
        return self::countViewStats('bounceHits' , 'M', 12);
    }

    public static function weeklyBotStats() {
        return self::countViewStats('botHits' , 'D', 7);
    }

    public static function monthlyBotStats() {
        return self::countViewStats('botHits' , 'D', 30);
    }

    public static function yearlyBotStats() {
        return self::countViewStats('botHits' , 'M', 12);
    }

    public function behaviors() {
        return [
            [
                'class' => 'cii\behavior\ExtendableBehavior',
                'throwErrorUnboxed' => true,
            ],

            [
                'class' => 'cii\behavior\DatetimeBehavior',
                'create' => 'created'
            ]
        ];
    }
}

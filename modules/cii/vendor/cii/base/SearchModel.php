<?php

namespace cii\base;

use Yii;
use yii\base\InvalidConfigException;

class SearchModel extends \yii\base\DynamicModel {
	protected $model;
	protected $attributeFormatters = [];
	protected $attributeLabels = [];

	public $basePath = '@vendor/cii/views/search';

	public function __construct($model, $config = []) {
		parent::__construct([], $config);

		if(is_string($model)) {
			$model = new $model();
		}
		$this->model = $model;
	}

	public function attributeLabels() {
		return $this->model->attributeLabels() + $this->attributeLabels;
	}

	public function addAttribueLabels($arr) {
		$this->attributeLabels = $this->attributeLabels + $arr;
	}

	protected function addAttribute($name, $formatter, $rules, $attributes) {
		if(in_array($name, $this->attributes())) {
			throw new InvalidConfigException('Attribute is already defined!');
		}

		$this->defineAttribute($name);
		foreach($rules as $rule) {
			$r = $rule[0];
			unset($rule[0]);
			$this->addRule($name, $r, $rule);
		}

		$this->attributeFormatters[$name] = (array)$formatter;
		if(is_null($attributes) || count($attributes) == 0) {
			$attributes = [$name];
		}

		$this->attributeFormatters[$name]['attributes'] = $attributes;
	}

	public function booleanFilter($name, $attributes = null) {
		$this->addAttribute($name, 'boolean', [
			['in', 'range' => array_keys($this->getEnabledTypes())]
		], $attributes);
	}

	public function getEnabledTypes() {
		return [
			null => Yii::t('yii', 'All'),
			1 => Yii::t('yii', 'Enabled'),
			2 => Yii::t('yii', 'Disabled'),
		];
	}

	public function getLanguages() {
		return Yii::$app->cii->language->getLanguagesForDropdown();
	}

	public function stringFilter($name, $attributes = null) {
		$this->addAttribute($name, 'string', [
			['string']
		], $attributes);
	}
	
	public function languageFilter($name, $attributes = null) {
		$this->addAttribute($name, 'language', [
			['in', 'range' => array_keys($this->getLanguages())]
		], $attributes);
	}


	public function applyFilter($query) {
		foreach($this->attributeFormatters as $name => $formatter) {
			
			if($this->$name) {
				$where = ['or'];
				foreach($formatter['attributes'] as $attr) {
					if($formatter[0] == 'string') {
						$where[] = ['like', $this->model->tableName() . '.' . $attr, $this->$name];
					}else {
						$where[] = [$this->model->tableName() . '.' . $attr => $this->$name];
					}
				}

				$query->andFilterWhere($where);
			}
			
		}


		return $query;
	}


	public function render($view) {
		return $view->render($this->basePath . '/index.php', [
			'model' => $this,
			'attributes' => $this->attributeFormatters
		]);
	}
}
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

		$this->attributeFormatters[$name]['attributes'] = (array)$attributes;
	}

	public function booleanFilter($name, $attributes = null) {
		$values = $this->getEnabledTypes();

		$this->addAttribute($name, ['boolean', 'template' => 'in', 'values' => $values], [
			['in', 'range' => array_keys($values)]
		], $attributes);
	}

	public function getEnabledTypes() {
		return [
			null => Yii::t('yii', 'All'),
			1 => Yii::t('yii', 'Yes'),
			0 => Yii::t('yii', 'No'),
		];
	}
	
	public function nullFilter($name, $attributes = null) {
		$values = $this->getEnabledTypes();

		$this->addAttribute($name, ['null', 'template' => 'in', 'values' => $values], [
			['in', 'range' => array_keys($values)]
		], $attributes);
	}

	public function stringFilter($name, $attributes = null) {
		$this->addAttribute($name, 'string', [
			['string']
		], $attributes);
	}
	
	public function languageFilter($name, $attributes = null) {
		$values = Yii::$app->cii->language->getLanguagesForDropdown();

		$this->addAttribute($name, ['language', 'template' => 'in', 'values' => $values], [
			['in', 'range' => array_keys($values)]
		], $attributes);
	}


	public function applyFilter($query) {
		foreach($this->attributeFormatters as $name => $formatter) {
			$value = $this->$name;
			if(strlen($value) != 0) {
				$where = ['or'];
				$func = 'andWhere';

				foreach($formatter['attributes'] as $attr) {
					if($formatter[0] == 'string') {
						$func = 'andFilterWhere';
						$where[] = ['like', $this->model->tableName() . '.' . $attr, $value];
					}else if($formatter[0] == 'null') {
						if($value == 0) {
							$where[] = [$this->model->tableName() . '.' . $attr => null];
						}else {
							$where[] = ['not', [$this->model->tableName() . '.' . $attr => null]];
						}
					}else {
						$where[] = [$this->model->tableName() . '.' . $attr => $value];
					}
				}

				$query->$func($where);
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
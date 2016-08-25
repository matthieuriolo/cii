<?php

namespace cii\base;

class Model extends \yii\base\Model {
	protected $_formName;
	
	public function setFormName($name) {
		$this->_formName = $name;
	}

	public function formName() {
		if($this->_formName) {
			return $this->_formName;
		}

		return parent::formName();
	}

	public function setContentFormName($content, $position) {
		$this->setFormName('shadow-' . ($position ?: '*') . '-' . $content->content->id);
	}
}
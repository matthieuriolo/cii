<?php
namespace cii\widgets;

use Yii;
use yii\base\Arrayable;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\widgets\DetailView as Base_DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

class EditView extends DetailView {
    public $form;
	public $template = '<div class="col-md-{column_width}">{value}</div>';
    public $rowTemplate = '<div class="row">{rows}</div>';

    public function init() {
        if(!$this->form) {
            throw new InvalidConfigException();
        }

        parent::init();
    }

    protected function renderAttribute($attribute, $index) {
        if (is_string($this->template)) {
            return strtr($this->template, [
                '{column_width}' => $this->getColumnWidth(),
                '{value}' => $attribute->getEditable($this->model, $this->form),
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }
}

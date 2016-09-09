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

use cii\fields\BaseField;

class DetailView extends Base_DetailView {
	public $template = '<div class="col-md-{column_width}"><div class="form-group"><div class="control-label"><strong>{label}</strong></div><div class="form-control-static">{value}</div></div></div>';
    public $rowTemplate = '<div class="row">{rows}</div>';
    public $columns = 2;
    public $columns_max = 12;

    public function init() {
        if(!$this->columns || $this->columns < 1) {
            $this->columns = 2;
        }

        if($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        $this->normalizeAttributes();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    protected function getColumnWidth() {
        return floor($this->columns_max / $this->columns);
    }
    
    public function run() {
        $rows = [];
        $i = 0;
        foreach($this->attributes as $attribute) {
            $rows[] = $this->renderAttribute($attribute, $i++);
        }

        for($i = 0; $i < count($rows); $i+=$this->columns) {
            $rowTemplate = '';

            for($a = $i; $a < $i + $this->columns; $a++) {
            	if(isset($rows[$a])) {
            		$rowTemplate .= $rows[$a];
            	}
            }

        	echo strtr($this->rowTemplate, [
                '{rows}' => $rowTemplate,
            ]);
        }
    }

    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            return strtr($this->template, [
                '{column_width}' => $this->getColumnWidth(),
                '{label}' => $attribute->label,
                '{value}' => $attribute->getView($this->model),
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }

    protected function normalizeAttributes()
    {
        if ($this->attributes === null) {
            if ($this->model instanceof Model) {
                $this->attributes = $this->model->attributes();
            } elseif (is_object($this->model)) {
                $this->attributes = $this->model instanceof Arrayable ? array_keys($this->model->toArray()) : array_keys(get_object_vars($this->model));
            } elseif (is_array($this->model)) {
                $this->attributes = array_keys($this->model);
            } else {
                throw new InvalidConfigException('The "model" property must be either an array or an object.');
            }
            sort($this->attributes);
        }

        foreach ($this->attributes as $i => $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                    throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:type" or "attribute:type:label"');
                }
                $attribute = [
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'text',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }

            if (!is_array($attribute)) {
                throw new InvalidConfigException('The attribute configuration must be an array.');
            }

            if (isset($attribute['visible']) && !$attribute['visible']) {
                unset($this->attributes[$i]);
                continue;
            }

            if (!isset($attribute['format']) || !Yii::$app->cii->hasFieldType($attribute['format'])) {
                $attribute['format'] = 'text';
            }

            $type = $attribute['format'];
            unset($attribute['format']);
            
            if (isset($attribute['attribute'])) {
                $attributeName = $attribute['attribute'];
                if (!isset($attribute['label'])) {
                    $attribute['label'] = $this->model instanceof Model ? $this->model->getAttributeLabel($attributeName) : Inflector::camel2words($attributeName, true);
                }
                if (!array_key_exists('value', $attribute)) {
                    $attribute['value'] = ArrayHelper::getValue($this->model, $attributeName);
                }
            } elseif (!isset($attribute['label']) || !array_key_exists('value', $attribute)) {
                throw new InvalidConfigException('The attribute configuration requires the "attribute" element to determine the value and display label.');
            }

            if($attribute = Yii::$app->cii->createFieldObject($type, $attribute)) {
                if($attribute->isVisible()) {
                    $this->attributes[$i] = $attribute;
                }
            }else {
                unset($this->attributes[$i]);
            }
        }
    }
}

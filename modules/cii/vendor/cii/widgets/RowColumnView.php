<?php
namespace cii\widgets;

use Yii;
use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class RowColumnView extends Widget {
	public $template = '<div class="col-md-{column_width}">{content}</div>';
    public $rowTemplate = '<div class="row">{rows}</div>';
    public $columns = 2;
    public $columns_max = 12;

    public $items = [];
    
    public function init() {
        if(!is_int($this->columns) || $this->columns < 1) {
            throw new InvalidConfigException('The property "columns" must be an integer and greater or equal 1');
        }

        if(!is_int($this->columns_max) || $this->columns_max > 12) {
            throw new InvalidConfigException('The property "columns" must be an integer and less or equal 12');
        }
        
        $this->normalizeItems();
    }

    protected function getColumnWidth() {
        return floor($this->columns_max / $this->columns);
    }
    
    public function run() {
        $rows = [];
        $i = 0;
        foreach($this->items as $item) {
            $rows[] = $this->renderItem($item, $i++);
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

    protected function renderItem($attribute, $index) {
        if(is_string($this->template)) {
            return strtr($this->template, [
                '{column_width}' => $this->getColumnWidth(),
                '{content}' => $attribute['content'],
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }

    protected function normalizeItems() {
        foreach($this->items as $i => $item) {
            if(!is_array($item)) {
                throw new InvalidConfigException('The item configuration must be an array.');
            }

            if(isset($item['visible']) && !$item['visible']) {
                unset($this->items[$i]);
                continue;
            }

            if(!isset($item['content']) || !array_key_exists('content', $item)) {
                throw new InvalidConfigException('The item configuration requires the "content" element');
            }
        }
    }
}

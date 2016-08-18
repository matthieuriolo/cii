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

class DetailView extends Base_DetailView {
	public $template = '<div class="col-md-6"><div class="form-group"><div class="control-label"><strong>{label}</strong></div><div class="form-control-static">{value}</div></div></div>';
    public $rowTemplate = '<div class="row">{rows}</div>';

    public function run()
    {
        $rows = [];
        $i = 0;
        foreach ($this->attributes as $attribute) {
            $rows[] = $this->renderAttribute($attribute, $i++);
        }

        for($i = 0; $i < count($rows); $i+=2) {
        	$rowTemplate = $rows[$i];
        	if(isset($rows[$i+1])) {
        		$rowTemplate .= $rows[$i+1];
        	}

        	echo strtr($this->rowTemplate, [
                '{rows}' => $rowTemplate,
            ]);
        }
    }
}

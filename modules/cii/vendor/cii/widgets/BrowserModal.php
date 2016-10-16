<?php
namespace cii\widgets;

use Yii;
use cii\helpers\Html;
use cii\helpers\Url;
use cii\helpers\FileHelper;

use yii\base\InvalidConfigException;

class BrowserModal extends iFrameModal {
	public $mimeTypes = null;
	public $callbackSubmit = null;

    public function init() {
    	$url = [Yii::$app->seo->relativeAdminRoute('modules/cii/popup/browser')];

    	if($this->mimeTypes) {
    		$url['types'] = implode(',', $this->mimeTypes);
    	}

    	$this->src = Url::toRoute($url, true);
        
        if(!$this->header) {
            $this->header = Yii::p('cii', 'File Browser');
        }

        parent::init();

        $this->size .= ' modal-browser';
    }


    public function run() {
    	echo Html::hiddenInput('browser_modal_field', null, [
            'id' => $this->id . '_field'
        ]);

        echo '<div class="form-group buttons">'
			. Html::a(
		        Yii::p('cii', 'Cancel'),
		        '#',
		        [
		        	'data-dismiss' => 'modal',
		        	'class' => 'btn btn-warning'
		        ]
		    )
			. '&nbsp;'
			. Html::a(Yii::p('cii', 'Select'), '#', [
				'class' => 'btn btn-primary disabled',
				'id' => $this->id . '_submit',
				'data-dismiss' => 'modal',
				'onclick' => '(function() {
					' . ($this->callbackSubmit ? $this->callbackSubmit : '') . '
				})();'
			])
	    . '</div>';

    	return parent::run();
    }
}

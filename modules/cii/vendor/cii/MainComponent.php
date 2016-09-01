<?php
namespace cii;

use Yii;
use yii\base\Component;

use yii\base\InvalidConfigException;

use cii\helpers\SPL;
use app\modules\cii\models\Configuration;

class MainComponent extends Component {
	public $layout = ['class' => 'cii\components\layout'];
	public $package = ['class' => 'cii\components\package'];
	public $route = ['class' => 'cii\components\route'];
	public $language = ['class' => 'cii\components\language'];
	
	public function init() {
		$this->layout = Yii::createObject($this->layout);
		$this->package = Yii::createObject($this->package);
		$this->route = Yii::createObject($this->route);
		$this->language = Yii::createObject($this->language);
		parent::init();
	}

	public function clearCache() {
		$this->package->clearCache();
		$this->route->clearCache();
		$this->layout->clearCache();
		$this->language->clearCache();
	}
	
	public function powered() {
        return \Yii::t('app', 'Content Managment System {cii}', [
            'cii' => '<a href="http://www.yiiframework.com/" rel="external">Cii</a>'
        ]);
    }

    public function getSettingTypes() {
        $ret = [];
        foreach($this->package->getSettingTypes() as $val) {
            array_push($ret, $val);
        }

        foreach($this->layout->getSettingTypes() as $val) {
            array_push($ret, $val);
        }

        return $ret;
    }

    public function mail($class, $to, $data) {
    	if(is_string($class)) {
    		$class = ['class' => $class];
    	}

    	$mailer = Yii::$app->mailer;
    	$class['mailer'] = $mailer;

    	$info = Yii::createObject($class);
    	if(!SPL::hasInterface($info, 'cii\base\MailInterface')) {
    		throw new UserException('Mail (' . $class['class'] . ') class does not implement the cii\base\MailInterface');
    	}

    	$msg = $mailer->compose();

    	$embeds = [];
    	foreach($info->getEmbeds() as $embed) {
    		if(is_file($embed)) {
	    		$embeds[$msg->embed($embed)] = $embed;
	    	}
    	}

    	foreach($info->getAttachements() as $attachment) {
    		if(is_array($attachment) && isset($attachment['content'])) {
    			$msg->attachContent($attachment[0], $attachment);
    		}else if(is_file($attachment)) {
    			$msg->attach($attachment);
    		}
    	}
		

		$msg->setSubject($info->getSubject($data));
		$msg->setHtmlBody($info->getHtml($data, $embeds));
		$msg->setTextBody($info->getText($data));
		$msg->setTo($to);

		$msg->setFrom(Yii::$app->cii->setting('cii', 'sender'));
		return $msg->send();
    }


    public function setting($type, $package, $key, $defaultValue = null) {
    	$identifier = null;
        $extension = 'app\modules\cii\models\\' . ucfirst($type);

        $extension = $extension::find()
            ->joinWith('extension as ext')
            ->where(['ext.name' => $package])
            ->one()
        ;

        if(!$extension) {
            throw new InvalidConfigException();
        }

        $model = Configuration::find()
    		->where([
    			'extension_id' => $extension->extension_id,
    			'name' => $key,
    		])
    		->one();
    	
    	if($model && $model->value) {
    		return $model->getPreparedValue();
    	}

    	if(!$model && func_num_args() == 3) {
    		$types = $extension->getSettings();

    		if(!isset($types[$key])) {
                throw new InvalidConfigException();
    		}

    		if(isset($types[$key]['default'])) {
    			return $types[$key]['default'];
    		}

    		return null;
    	}

    	return $defaultValue;
    }
}

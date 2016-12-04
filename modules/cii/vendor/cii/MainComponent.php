<?php
namespace cii;

use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

use cii\web\SecurityException;
use cii\helpers\SPL;
use app\modules\cii\models\extension\Configuration;

class MainComponent extends Component {
	public $layout = ['class' => 'cii\components\layout'];
	public $package = ['class' => 'cii\components\package'];
	public $route = ['class' => 'cii\components\route'];
	public $language = ['class' => 'cii\components\language'];
	public $image = [
        'class' => 'cii\components\ImageDriver',
        'driver' => 'GD',
    ];

    public $extension = ['class' => 'cii\components\extension'];


	public function init() {
		$this->layout = Yii::createObject($this->layout);
		$this->package = Yii::createObject($this->package);
		$this->route = Yii::createObject($this->route);
		$this->language = Yii::createObject($this->language);
        $this->extension = Yii::createObject($this->extension);

        $this->image = Yii::createObject($this->image);
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

    protected $_settingTypes;
    public function getSettingTypes() {
        if(!$this->_settingTypes) {
            $ret = [];
            foreach($this->package->getSettingTypes() as $val) {
                array_push($ret, $val);
            }

            foreach($this->layout->getSettingTypes() as $val) {
                array_push($ret, $val);
            }

            return $this->_settingTypes = $ret;
        }

        return $this->_settingTypes;
    }

    protected $_fieldTypes;
    public function getFieldTypes() {
        if(!$this->_fieldTypes) {
            return $this->_fieldTypes = $this->package->getFieldTypes()/* +
                $this->layout->getFieldTypes()*/;
        }

        return $this->_fieldTypes;
    }

    public function hasFieldType($type) {
        $type = strtolower($type);
        $values = $this->getFieldTypes();
        if(isset($values[$type])) {
            return true;
        }

        return false;
    }

    public function createFieldObject($type, $data = []) {
        $type = strtolower($type);
        if(self::hasFieldType($type)) {
            $data['class'] = $this->getFieldTypes()[$type];
            return Yii::createObject($data);
        }

        return null;
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


        Yii::info('Sending ' . (is_object($class) ? $class::className() : $class['class']) . ' mail to ' . VarDumper::export($to) , 'mail');
		return $msg->send();
    }


    public function setting($type, $package, $key, $defaultValue = null) {
    	$identifier = null;
        $extension = 'app\modules\cii\models\extension\\' . ucfirst($type);

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
        
    	if($model && !is_null($model->value)) {
    		return $model->getPreparedValue();
    	}

    	if(!$model && func_num_args() == 3) {
    		$types = $extension->getSettings();

    		if(!isset($types[$key])) {
                throw new InvalidConfigException('The setting ' . $key . ' does not exists');
    		}

    		if(isset($types[$key]['default'])) {
    			return $types[$key]['default'];
    		}

    		return null;
    	}

    	return $defaultValue;
    }


    public function thumbnail($file, $width, $height) {
        $web = Yii::$app->basePath;
        if(!is_readable($file) || strpos($file, $web) !== 0) {
            throw new SecurityException();
        }
        
        $origPath = $path = substr($file, strlen($web) + 1);
        $path = dirname($path);

        $filename = basename($file);
        $suffix = '-' . $width . 'X' . $height;
        if(($pos = strrpos($filename, '.')) !== false) {
            $filename = substr($filename, 0, $pos) . $suffix . substr($filename, $pos);
        }else {
            $filename .= $suffix;
        }


        $thumbnail = $web . '/thumbnails';
        $thumbnailPath = $thumbnail . '/' . $path . '/' . $filename;
        $thumbnailWebPath = substr($thumbnailPath, strlen($web) + 1);
        if(file_exists($thumbnailPath) && time() - filemtime($thumbnailPath) < Yii::$app->params['thumnail_duration']) {
            return $thumbnailWebPath;
        }


        if(
            ($img = $this->image->load($file))
            &&
            $img->resize($width, $height, \cii\components\drivers\Kohana\Image::ADAPT)
        ) {
            FileHelper::createDirectory(dirname($thumbnailPath));

            if($img->save($thumbnailPath)) {
                Yii::info('Creating thumbnail for ' . $origPath, 'thumbnail');
                return $thumbnailWebPath;
            }
        }

        Yii::error('Failed to create thumbnail for ' . $origPath, 'thumbnail');
        return $origPath;
    }


    public function flushThumbnails() {
        $path = Yii::$app->basePath . '/thumbnails';

        foreach(scandir($path) as $inode) {
            if(substr($inode, 0, 1) == '.') {
                continue;
            }


            $inodePath = $path . '/' . $inode;
            if(is_file($inodePath)) {
                unlink($inodePath);
            }else if(is_dir($inodePath)) {
                FileHelper::removeDirectory($inodePath);
            }
        }
    }
}

<?php
namespace cii\web\routes;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

use app\modules\cii\models\Route;

class RegexRoute extends AbstractRoute {
    public $match;
    public $replace;

    public function init() {
        if($this->match === null) {
            throw new InvalidConfigException('RegexRoute::match must be set.');
        }

        if($this->replace === null) {
            throw new InvalidConfigException('RegexRoute::replace must be set.');
        }
        
        parent::init();
    }

    public function parseRoute($manager, $request) {
        $sub = substr($this->route, $this->offset);
        $pattern = '/' . $this->match . '/';
        $matches = [];
    	if(preg_match($pattern, $sub, $matches)) {
            $replace = str_replace('$*', ltrim(substr($sub, strlen($matches[0])), '/'), $this->replace);
            $sub = substr($sub, 0, strlen($matches[0]));
            return [preg_replace($pattern, $replace, $sub), [], $this];
        }

        return false;
    }

    public function getBaseRoute() {
        $offset = $this->offset;
        $sub = substr($this->route, $this->offset);
        $pattern = '/' . $this->match . '/';
        $matches = [];
        if(preg_match($pattern, $sub, $matches)) {
            $offset += strlen($matches[0]);
        }
        
        $ret = substr($this->route, 0, $offset);
        return trim($ret, '/');
    }
}

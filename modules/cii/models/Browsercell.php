<?php


namespace app\modules\cii\models;

use Yii;
use cii\base\Model;
use yii\captcha\Captcha;
use cii\helpers\Html;
use cii\helpers\Url;

class Browsercell extends Model {
    public $file;
    public $baseUrl;
    public $basePath;
    public $suffixImages = ['png', 'jpg', 'jpeg'];

    public function init() {
        if(!$this->baseUrl && Yii::$app->seo) {
            $this->baseUrl = Yii::$app->seo->relativeAdminRoute('modules/cii/browser');
        }
    }

    public function getName() {
        return basename($this->file);
    }
    
    public function getSuffix() {
        $tks = explode('.', $this->getName());
        return end($tks);
    }

    public function isDirectory() {
        return is_dir($this->file);
    }

    public function getFilePath() {
        return substr($this->file, strlen($this->basePath) + 1);
    }

    public function getDisplayContent($renameModalId) {
        return '<div class="element is-' . ($this->isDirectory() ? 'directory' : 'file') . '">' .
                $this->getDisplayPreview() . 
                '<div class="buttons">' .
                    ($this->isDirectory() ? '<a class="button-open-directory" href="' . Url::to([$this->baseUrl . '/index', 'path' => $this->getFilePath()]) . '"><i class="glyphicon glyphicon-share"></i></a>' : '')
                    . (!$this->isDirectory() ? '<a class="button-download" href="' . Url::to([$this->baseUrl . '/download', 'path' => $this->getFilePath()]) . '"><i class="glyphicon glyphicon-cloud-download"></i></a>' : '')
                    . '<a class="button-rename" data-target="#' . $renameModalId . '" data-toggle="modal" data-original-name="'.Html::encode($this->getName()).'"><i class="glyphicon glyphicon-edit"></i></a>'
                    . '<a class="button-remove" href="' . Url::to([$this->baseUrl . '/remove', 'path' => $this->getFilePath()]) . '"><i class="glyphicon glyphicon-remove"></i></a>'
                    .

                '</div>' .
                '<div class="name"><span>' . Html::encode($this->getName()) .'</span></div>' .
                
            '</div>';
    }
    

    public function isImage() {
        if(in_array($this->getSuffix(), $this->suffixImages)) {
            return @getimagesize($this->file);
        }

        return false;
    }

    public function getDisplayPreview() {
        if($this->isDirectory()) {
            return '<div class="preview-container"><i class="glyphicon glyphicon-folder-close"></i></div>';
        }else if($this->isImage()) {
            return '<div class="preview-container"><img src="' . Yii::$app->cii->thumbnail($this->file, 80, 60) . '" class="preview-image"></div>';
        }

        return '<div class="preview-container"><i class="glyphicon glyphicon-file"></i></div>';
    }
}

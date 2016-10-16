<?php


namespace app\modules\cii\models;

use Yii;
use cii\base\Model;
use yii\captcha\Captcha;
use cii\helpers\Html;
use cii\helpers\Url;
use cii\helpers\FileHelper;

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

    public function isWritable() {
        return is_writable($this->file);
    }

    protected function getUrl($path, $types = null, $iframe = null) {
        $url = [$this->baseUrl . '/' . $path, 'path' => $this->getFilePath()];
        if($types) {
            $url['types'] = implode(',', $types);
        }

        if($iframe) {
            $url['iframe'] = $iframe;
        }

        return Url::to($url);
    }

    public function getDisplayContent($renameModalId, $types = null, $iframe = null) {
        $type = FileHelper::getMimeType($this->file);
        return '<div class="element is-' . ($this->isDirectory() ? 'directory' : 'file')
                . ' ' . ($types && !in_array($type, $types) ? 'disabled' : '')
                . '">' .
                $this->getDisplayPreview() . 
                '<div class="buttons">' .
                    ($this->isDirectory() ? '<a title="Open directory" class="button-open-directory" href="' . $this->getUrl('index', $types, $iframe) . '"><i class="glyphicon glyphicon-share"></i></a>' : '')
                    . (!$this->isDirectory() ? '<a title="Download file" class="button-download" href="' . $this->getUrl('download', $types, $iframe) . '"><i class="glyphicon glyphicon-cloud-download"></i></a>' : '')
                    . (
                        $this->isWritable() ?
                        '<a title="Rename element" class="button-rename" href="#" data-target="#' . $renameModalId . '" data-toggle="modal" data-original-name="'. Html::encode($this->getName()) .'"><i class="glyphicon glyphicon-edit"></i></a>'
                        . '<a title="Remove element" class="button-remove" href="' . $this->getUrl('remove', $types, $iframe) . '"><i class="glyphicon glyphicon-remove"></i></a>'
                        :
                        ''
                    ) . 
                '</div>' .
                '<div class="permissions">' .
                    ($this->isWritable() ? '' : '<i title="Not writable" class="glyphicon glyphicon-lock text-danger"></i>')
                . '</div>' .
                '<div class="name">' . Html::encode($this->getName()) .'</div>' .
                
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

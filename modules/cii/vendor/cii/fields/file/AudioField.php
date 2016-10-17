<?php
namespace cii\fields\file;

use Yii;
use cii\helpers\FileHelper;
use cii\fields\BrowserField;

class AudioField extends BrowserField {
	protected function getMimeTypes() {
		return FileHelper::$audioMimeTypes;
	}
}

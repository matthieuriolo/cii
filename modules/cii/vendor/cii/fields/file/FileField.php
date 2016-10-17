<?php
namespace cii\fields\file;

use Yii;
use cii\fields\BrowserField;

class FileField extends BrowserField {
	protected function getMimeTypes() {
		return null;
	}
}

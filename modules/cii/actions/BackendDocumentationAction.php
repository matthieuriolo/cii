<?php 

namespace app\modules\cii\actions;

use Yii;
use yii\web\ViewAction;

class BackendDocumentationAction extends ViewAction {
	public $viewPrefix = 'views/documentation';


    protected function resolveViewName() {

        $viewName = Yii::$app->request->get($this->viewParam, $this->defaultView);

        if (!is_string($viewName) || !preg_match('~^\w(?:(?!\/\.{0,2}\/)[\w\/\-\.])*$~', $viewName)) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException("The requested view \"$viewName\" must start with a word character, must not contain /../ or /./, can contain only word characters, forward slashes, dots and dashes.");
            } else {
                throw new NotFoundHttpException(Yii::t('yii', 'The requested view "{name}" was not found.', ['name' => $viewName]));
            }
        }

        $pathPrefix = '@app';
        if(($pos = strpos($viewName, '/')) !== false) {
        	$pathPrefix .= '/modules/' . substr($viewName, 0, $pos);
        	$viewName = substr($viewName, $pos + 1);
        }else {
        	$pathPrefix .= '/modules/cii';
        }

        return $pathPrefix . '/' . $this->viewPrefix . '/' . $viewName;
    }
}
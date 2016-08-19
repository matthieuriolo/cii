<?php
namespace cii\web;

use Yii;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentRoute;

use cii\routes\ControllerRoute;
use cii\helpers\SPL;

class View extends \yii\web\View {

	public function init() {
		$dbmodel = Yii::$app->seo;
		if($dbmodel instanceof ControllerRoute) {
			$dbmodel = $dbmodel->getModel();
	        $this->title = $dbmodel->route->title;
	        
	        if($dbmodel instanceof ContentRoute) {
		        $model = $dbmodel->content->outbox();
		        
		        if(SPL::hasInterface($model, 'app\modules\cii\base\ContentInterface')) {
		            if(!empty($dbmodel->keys)) {
		                $this->registerMetaTag([
		                    'name' => 'keywords',
		                    'content' => $dbmodel->keys
		                ]);
		            }else if($content = Yii::$app->cii->setting('cii', 'metakeys')) {
		                $this->registerMetaTag([
		                    'name' => 'keywords',
		                    'content' => $content
		                ]);
		            }

		            if(!empty($dbmodel->description)) {
		                $this->registerMetaTag([
		                    'name' => 'description',
		                    'content' => $dbmodel->description
		                ]);

		                $this->registerMetaTag([
		                    'property' => 'og:description',
		                    'content' => $dbmodel->description
		                ]);
		            }else if($content = Yii::$app->cii->setting('cii', 'metadescription')) {
		                $this->registerMetaTag([
		                    'name' => 'description',
		                    'content' => $content
		                ]);

		                $this->registerMetaTag([
		                    'property' => 'og:description',
		                    'content' => $content
		                ]);
		            }

		            if(!empty($dbmodel->type)) {
		                $this->registerMetaTag([
		                    'property' => 'og:type',
		                    'content' => $dbmodel->type
		                ]);
		            }else {
		                $this->registerMetaTag([
		                    'property' => 'og:type',
		                    'content' => 'website'
		                ]);
		            }

		            if(!empty($dbmodel->image)) {
		                $this->registerMetaTag([
		                    'property' => 'og:image',
		                    'content' => $dbmodel->image
		                ]);
		            }

		            if(!empty($dbmodel->robots)) {
		                $this->registerMetaTag([
		                    'name' => 'robots',
		                    'content' => $dbmodel->robots
		                ]);
		            }
		        }
		    }
		}

		parent::init();
	}

	public function getContents($name = null) {
		return Yii::$app->cii->layout->getContents($name);
	}
}

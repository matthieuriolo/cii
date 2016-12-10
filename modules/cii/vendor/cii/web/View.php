<?php
namespace cii\web;

use Yii;
use app\modules\cii\models\Content;
use app\modules\cii\models\ContentRoute;

use cii\routes\ControllerRoute;
use cii\helpers\SPL;

class View extends \yii\web\View {
	public function isAdminArea() {
		if(Yii::$app->seo && Yii::$app->seo->findRoute('app\modules\cii\routes\Backend', false)) {
			return true;
		}

		return false;
	}

	public function init() {
		$id = Yii::$app->cii->package->setting('cii', 'google_site_verification_id');
		if(!empty($id)) {	
			$this->registerMetaTag([
	            'name' => 'google-site-verification',
	            'content' => $id
	        ]);
	    }

		$id = Yii::$app->cii->package->setting('cii', 'google_analytics_id');
	    if(!empty($id)) {
	        $this->registerJs("<!-- Google Analytics -->
	        <script>
	        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	        ga('create', '" . $id . "', 'auto');
	        ga('send', 'pageview');
	        </script>
	        <!-- End Google Analytics -->", View::POS_HEAD);
    	}

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
		            }else if($content = Yii::$app->cii->package->setting('cii', 'metakeys')) {
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
		            }else if($content = Yii::$app->cii->package->setting('cii', 'metadescription')) {
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

		return parent::init();
	}

	public function getContents($name = null) {
		return Yii::$app->cii->layout->getContents($name);
	}

	public function renderShadow($content, $position) {
		$info = $content->getShadowInformation();
		$controller = Yii::$app->createController($info['route'])[0];
		return $controller->$info['action']($content, $position);
	}

	public function renderShadows($position) {
		$contents = $this->getContents($position);
		$ret = '';
		foreach($contents as $content) {
			$ret .= $this->renderShadow($position);
		}

		return $ret;
	}



	public function getIsPjax() {
		return Yii::$app->request->getIsPjax();
	}

	public function pjaxid() {
		return Yii::$app->request->pjaxid();
	}
}

<?php
namespace cii\widgets;

use Yii;
use yii\base\Widget;
use cii\helpers\Url;
use cii\helpers\Html;

class BackendMenu extends Widget {
    public function run() {
        echo '<div class="list-group list-group-margin-top">
            <span class="list-group-item">',
                Html::textInput(
                    '',
                    '', [
                    'placeholder' => Yii::t('app', 'Filter menu...'),
                    'class' => "form-control",
                    'data-controller' => "filter-nested-menu"
                ]),
            '</span>';

        foreach(Yii::$app->cii->package->all(true) as $pkg) {
            $items = $pkg->getBackendItems();
            Html::printNestedMenu($items, function($item) {
                $itemUrl = Url::toRoute($item['url']);
                $appUrl = Yii::$app->getRequest()->getUrl();
                
                if($itemUrl == $appUrl) {
                    return true;
                }else {
                    $itemUrl = $item['url'][0];
                    $appUrl = Yii::$app->urlManager->getCalledRoute();
                    
                    if(strpos($itemUrl, '/admin/modules') === 0) {
                        if(strpos(dirname($appUrl), dirname(ltrim($itemUrl, '/'))) === 0) {
                            return true;
                        }
                    }
                }

                return false;
            });
        }

        echo '</div>';
    }
}

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
            $this->printNestedMenu($items, function($item) {
                $itemUrl = ltrim($item['url'][0], '/');
                $appUrl = Yii::$app->urlManager->getCalledRoute();
                
                //url overlap
                if($itemUrl == $appUrl || (count(explode('/', $appUrl)) == 1 && $appUrl . '/index' == $itemUrl)) {
                    return true;
                //url is equal
                }else {
                    
                    if(count(explode('/', $appUrl)) >= 3 && count(explode('/', $itemUrl)) >= 3) {
                        if(strpos(dirname($appUrl), dirname($itemUrl)) === 0) {
                            return true;
                        }
                    }
                }

                return false;
            });
        }

        echo '</div>';
    }


    protected function printNestedMenu($prop, $selectedRouteCall = null, $lvl = 0) {
        $label = '';
        if(isset($prop['icon']) && !empty($prop['icon'])) {
            $label .= '<i class="' . $prop['icon'] . '"></i>&nbsp;';
        }

        $label .= Html::encode($prop['name']);

        

        if(isset($prop['visible']) && !$prop['visible']) {
            return false;
        }
        
        if(isset($prop['url'])) {
            $selected = false;
            

            if(is_callable($selectedRouteCall)) {
                if($selectedRouteCall($prop)) {
                    $selected = true;
                }
            }

            echo Html::a($label, $prop['url'], [
                'class' => 'list-group-item' . ($selected ? ' active' : ''),
            ]);
        }else {
            echo Html::tag('span', $label, [
                'class' => 'list-group-item',
            ]);
        }

        if(isset($prop['children']) && !empty($prop['children'])) {
            echo '<div class="list-group-item list-group-indented">';
            foreach ($prop['children'] as $sub) {
                $this->printNestedMenu($sub, $selectedRouteCall, $lvl + 1);
            }

            echo '</div>';
        }

        return true;
    }
}

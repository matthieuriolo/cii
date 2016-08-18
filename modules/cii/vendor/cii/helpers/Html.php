<?php
namespace cii\helpers;
use Yii;

class Html extends \yii\helpers\BaseHtml {
	public static function a($text, $url = null, $options = []){
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        
        return static::tag('a', $text, $options);
    }



    public static function boolean($value){
        $class = 'glyphicon-ok text-success';
        if(!$value) {
        	$class = 'glyphicon-remove text-danger';
        }

        return '<i class="glyphicon ' . $class . '"></i>';
    }


    public static function printNestedMenu($prop, $selectedRouteCall = null, $lvl = 0) {
        $label = '';
        if(isset($prop['icon']) && !empty($prop['icon'])) {
            $label .= '<i class="' . $prop['icon'] . '"></i>&nbsp;';
        }

        $label .= Html::encode($prop['name']);

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
                static::printNestedMenu($sub, $selectedRouteCall, $lvl + 1);
            }

            echo '</div>';
        }
    }
}

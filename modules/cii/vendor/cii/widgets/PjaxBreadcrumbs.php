<?php
namespace cii\widgets;

use Yii;
use yii\base\Widget;
use yii\widgets\Breadcrumbs;

class PjaxBreadcrumbs extends Widget {
    public $links;
    public $pjaxid;

    public function run() {
        $params = $this->links;
        foreach($params as &$param) {
            if(is_array($param)) {
                $param['url']['pjaxid'] = $this->pjaxid;
            }
        }

        echo Breadcrumbs::widget([
            'homeLink' => false,
            'links' => $params,
        ]);
    }
}

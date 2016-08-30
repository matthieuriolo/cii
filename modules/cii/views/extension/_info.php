<?php
use cii\helpers\Html;
use cii\widgets\DetailView;

$reflection = $model->getReflection();
?>
<br>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [	
        	'label' => Yii::p('cii', 'Name'),
        	'value' => $reflection->getName()
        ],

        [	
        	'label' => Yii::p('cii', 'Version'),
        	'value' => $reflection->getVersion()
        ],

        [   
            'label' => Yii::p('cii', 'Type'),
            'value' => $reflection->getType()
        ],

        [   
            'label' => Yii::p('cii', 'Message Type'),
            'value' => method_exists($reflection, 'getMessageType') ? $reflection->messageType : null,
            'visible' => $reflection->type === 'message'
        ],

        [   
            'label' => Yii::p('cii', 'Created'),
            'format' => 'datetime',
            'value' => $reflection->created
        ],

        [   
            'label' => Yii::p('cii', 'Installed'),
            'format' => 'datetime',
            'value' => $model->installed
        ],

        [	
        	'label' => Yii::p('cii', 'Enabled'),
        	'format' => 'html',
        	'value' => Html::boolean($reflection->isEnabled())
        ],

        [   
            'label' => Yii::p('cii', 'Author'),
            'value' => $reflection->authorName
        ],


        [   
            'label' => Yii::p('cii', 'Contact'),
            'format' => 'email',
            'value' => $reflection->authorMail
        ],

        [   
            'label' => Yii::p('cii', 'Website'),
            'format' => 'url',
            'value' => $reflection->authorSite
        ],
    ],
]) ?>


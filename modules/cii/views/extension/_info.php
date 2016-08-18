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
        	'label' => 'Name',
        	'value' => $reflection->getName()
        ],

        [	
        	'label' => 'Version',
        	'value' => $reflection->getVersion()
        ],

        [   
            'label' => 'Type',
            'value' => $reflection->getType()
        ],
        
        [   
            'label' => 'Created',
            'value' => $reflection->created
        ],

        [   
            'label' => 'Installed',
            'value' => $model->installed
        ],

        [	
        	'label' => 'Enabled',
        	'format' => 'html',
        	'value' => Html::boolean($reflection->isEnabled())
        ],

        [   
            'label' => 'Author',
            'value' => $reflection->authorName
        ],


        [   
            'label' => 'Contact',
            'format' => 'email',
            'value' => $reflection->authorMail
        ],

        [   
            'label' => 'Website',
            'format' => 'url',
            'value' => $reflection->authorSite
        ],
    ],
]) ?>


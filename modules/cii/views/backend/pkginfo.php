<?php
use cii\helpers\Html;
use cii\widgets\DetailView;
?>
<br>

<?= DetailView::widget([
    'model' => $package,
    'attributes' => [
        [	
        	'label' => 'Name',
        	'value' => $package->name
        ],

        [	
        	'label' => 'Version',
        	'value' => $package->version
        ],

        [   
            'label' => 'Created',
            'value' => $package->created
        ],

        [   
            'label' => 'Installed',
            'value' => $package->getInstalledVersion()->installed
        ],

        [	
        	'label' => 'Enabled',
        	'format' => 'html',
        	'value' => Html::boolean($package->isEnabled())
        ],


        [   
            'label' => 'Author',
            'value' => $package->authorName
        ],


        [   
            'label' => 'Contact',
            'format' => 'email',
            'value' => $package->authorMail
        ],

        [   
            'label' => 'Website',
            'format' => 'url',
            'value' => $package->authorSite
        ],
    ],
]) ?>


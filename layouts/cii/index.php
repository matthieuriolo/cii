<?php
return [
    'type' => 'layout',
    'name' => 'cii',
    'version' => '0.1',
    'description' => 'Default layout of Cii',
    'created' => '2016-08-21 22:40:11',

    'author' => [
        'name' => 'Matthieu Riolo',
        'email' => 'matthieu.riolo@gmail.com',
        'website' => 'http://www.ocsource.ch',
    ],

    'overview' => 'position_overview',

    'positions' => [
        'left' => Yii::l('cii', 'Left'),
        'right' => Yii::l('cii', 'Right'),
        'background' => Yii::l('cii', 'Background'),
        'navbar' => Yii::l('cii', 'Navigation'),
    ],
];
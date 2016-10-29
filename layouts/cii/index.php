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
        'background' => Yii::l('cii', 'Background'),
        
        'left' => Yii::l('cii', 'Left'),
        'right' => Yii::l('cii', 'Right'),
        'navbar' => Yii::l('cii', 'Navigation'),
        'footer' => Yii::l('cii', 'Footer'),

        'before_main' => Yii::l('cii', 'Before Main'),
        'after_main' => Yii::l('cii', 'After Main'),
        'inner_main' => Yii::l('cii', 'Inner Main'),
        'outer_main' => Yii::l('cii', 'Outer Main'),
    ],
];
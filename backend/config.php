<?php
return [
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'baseUrl' => '',
                'basePath' => '@www',
                'path' => 'images/blog',
                'name' => 'Images'
            ],
        ],
    ],
];
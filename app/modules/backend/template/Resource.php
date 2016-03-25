<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

$resource = [
    'author' => 'Lexnet.cn',
    'authorUri' => 'http://phalconcmf.com',
    'version' => '0.0.1',
    'uri' => 'http://phalconcmf.com',
    'location' => 'backend',
    'class_name' => 'Backend\\Template\\Module',
    'path' => '/backend/template/Module.php',
    'acl' => [
        [
            'controller' => 'index',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => 'active,unActive'
                ],
                [
                    'action' => 'install',
                    'sub_action' => ''
                ]
            ]
        ],
        [
            'controller' => 'sidebar',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => ''
                ]
            ]
        ],
        [
            'controller' => 'widget',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => 'publish, unpublish'
                ]
            ]
        ]
    ]
];
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
    'class_name' => 'Backend\\Slide\\Module',
    'path' => '/backend/slide/Module.php',
    'acl' => [
        [
            'controller' => 'index',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => ''
                ],
                [
                    'action' => 'new',
                    'sub_action' => 'edit, publish, unPublish'
                ],
                [
                    'action' => 'delete',
                    'sub_action' => ''
                ]
            ]
        ],
        [
            'controller' => 'manage-slide',
            'rules' => [
                [
                    'action' => 'slide',
                    'sub_action' => ''
                ],
                [
                    'action' => 'new',
                    'sub_action' => 'edit, publish, unPublish'
                ],
                [
                    'action' => 'delete',
                    'sub_action' => ''
                ]
            ]
        ]
    ]
];
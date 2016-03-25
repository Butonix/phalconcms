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
    'class_name' => 'Backend\\Menu\\Module',
    'path' => '/backend/menu/Module.php',
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
                    'sub_action' => ''
                ],
                [
                    'action' => 'edit',
                    'sub_action' => ''
                ],
                [
                    'action' => 'delete',
                    'sub_action' => '',
                ]
            ]
        ],
        [
            'controller' => 'menuitem',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => ''
                ],
                [
                    'action' => 'new',
                    'sub_action' => ''
                ],
                [
                    'action' => 'edit',
                    'sub_action' => ''
                ],
                [
                    'action' => 'delete',
                    'sub_action' => ''
                ]
            ]
        ]
    ]
];
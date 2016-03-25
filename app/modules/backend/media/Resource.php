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
    'class_name' => 'Backend\\Media\\Module',
    'path' => '/backend/media/Module.php',
    'acl' => [
        [
            'controller' => 'manager',
            'rules' => [
                [
                    'action' => 'index',
                    'sub_action' => ''
                ],
                [
                    'action' => 'new',
                    'sub_action' => 'edit, publish, unPublish'
                ]
            ]
        ]
    ]
];
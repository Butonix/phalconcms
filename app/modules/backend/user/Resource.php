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
    'class_name' => 'Backend\\User\\Module',
    'path' => '/backend/user/Module.php',
    'acl' => [
        [
            'controller' => 'profile',
            'rules' => [
                [
                    'action' => 'index'
                ]
            ]
        ]
    ]
];
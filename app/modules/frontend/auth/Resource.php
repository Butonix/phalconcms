<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

$resource = [
    'author' => 'Lexnet.cn',
    'authorUri' => 'http://phalconcmf.com',
    'version' => '0.0.1',
    'uri' => 'http://phalconcmf.com',
    'location' => 'frontend',
    'class_name' => 'Frontend\\Auth\\Module',
    'path' => '/frontend/auth/Module.php',
    'acl' => [
        [
            'controller' => 'index',
            'controller_name' => 'Index',
            'rules' => [
                [
                    'action' => 'index',
                    'action_name' => 'Front End',
                    'sub_action' => ''
                ]
            ]
        ]
    ]
];
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
    'class_name' => 'Backend\\Admin\\Module',
    'path' => '/backend/admin/Module.php',
    'acl' => [
        [
            'controller' => 'index',
            'controller_name' => 'm_admin_admin',
            'rules' => [
				[
					'action_name' => 'm_admin_admin',
					'action' => 'index'
				]
			]
        ]
    ]
];
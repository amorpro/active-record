<?php
/**
 * Created by PhpStorm.
 * User: Елена
 * Date: 01.05.2017
 * Time: 16:51
 * @var $secure
 */
return [
    'db' => [
        'class' => \ActiveRecord\db\Connection::class,
        'properties' => [
            'dsn' => 'mysql:host='.$secure['mysql']['mysqlcluster']['hostname'].';dbname=shared',
            'username' => $secure['mysql']['mysqlcluster']['username'],
            'password' => $secure['mysql']['mysqlcluster']['password'],
            'charset' => 'utf8',
        ]
    ],
];
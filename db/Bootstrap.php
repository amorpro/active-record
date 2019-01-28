<?php
/**
 * Created by PhpStorm.
 * User: AmorPro
 * Date: 28.01.2019
 * Time: 22:54
 */

namespace ActiveRecord\db;


use ActiveRecord\db\ActiveRecord;
use ActiveRecord\db\Connection;
use ActiveRecord\db\Query;

class Bootstrap
{


    public static function configure($host, $base, $user, $password, $encoding = 'utf8')
    {
        $db = new Connection();
        $db->dsn = 'mysql:host=' . $host . ';dbname=' . $base;
        $db->username = $user;
        $db->password = $password;
        $db->charset = $encoding;

        ActiveRecord::setDb($db);
        Query::setDb($db);
    }

}
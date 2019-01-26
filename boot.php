<?php
/**
 * Created by PhpStorm.
 * User: Елена
 * Date: 01.05.2017
 * Time: 16:38
 */
$db = \Core\Registry::db();

\ActiveRecord\db\ActiveRecord::setDb($db);
\ActiveRecord\db\Query::setDb($db);
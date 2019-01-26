Configuration in custom project
=

Create db connection:
```php
$db = new \ActiveRecord\db\Connection();
$db->dsn = 'mysql:host=sanddb.gtflixtv.com;dbname=shared';
$db->username = 'develop';
$db->password = 'develop@box';
$db->charset = 'utf8';
$db->schemaCache = Core_Registry::cache();
```

provide it to ActiveRecord classes:
```php
\ActiveRecord\db\ActiveRecord::setDb($db);
\ActiveRecord\db\Query::setDb($db);
```
Configuration in php-core
=

Add config to static_config.php
```php
    'db' => [
        'class' => '\ActiveRecord\db\Connection',
        'properties' => [
            'dsn' => 'mysql:host='.$secure['mysql']['mysqlcluster']['hostname'].';dbname=shared',
            'username' => $secure['mysql']['mysqlcluster']['username'],
            'password' => $secure['mysql']['mysqlcluster']['password'],
            'charset' => 'utf8',
        ]
    ],
```

Usage
=

http://www.yiiframework.com/doc-2.0/guide-db-active-record.html

Generator
=

Use generator to auto create active record models related with db tables:
 
prepare database structure:
```php
 $database = new \ActiveRecord\GeneratorDatabase('<databasename>');
 
 $database->addTable('<tablename>');
 $database->addTable('<tablename>');
 $database->addTable('<tablename>');
 ....
 
``` 
```php
$generator = new \ActiveRecord\Generator($db);

$generator->addDatabase($database1);
$generator->addDatabase($database2);
...
$generator->generate('<namespace>','<path>');
```
for example:

```php
$database = new \ActiveRecord\GeneratorDatabase('shared');
$database->addTable('website');
$database->addTable('rest_query');
$database->addTable('script_log');

$generator = new \ActiveRecord\Generator($db);
$generator->addDatabase($database);
$generator->generate('Model',__DIR__.'/../src/Model');
```
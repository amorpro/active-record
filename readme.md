Configuration in custom project
=
Configure the database connection
```php
\ActiveRecord\db\Bootstrap::configure($host, $base, $user, $password);
```

Usage
=

http://www.yiiframework.com/doc-2.0/guide-db-active-record.html

AR Generator
=

Create an ./bin/ar php script with next code

```php
// Ensure that the \ActiveRecord\db\Bootstrap::configure is called somewhere in bootstrap
include_once '../bootstrap.php';


$tables          = 'database_name:table1,table2';

$generator = new \ActiveGenerator\generator\ScriptHelper();
$generator->path = dirname(__DIR__) . '/src/Model';
$generator->prefix = 'Base';
$generator->generate($db->getSlavePdo(), $tables);
``` 

change the $tables variable with your list of tables.

This script will generate next structure of files:

| Files | Read-Only | Explanation |
| ----------------------------------------------- | -------------------------- |-------------------------- |
| `../src/Model/Base/BaseTable1.php`                    | yes |  Table1 base active record class |
| `../src/Model/Base/BaseTable1Query.php`               | yes |  Table1 query base class to retrieve the data from database  |
| `../src/Model/Base/BaseTable1Peer.php`                | yes |  Table1 base object to store field names, table name, constants related to model  |
| `../src/Model/Base/BaseTable2.php`                    | yes |  Table2 base active record class |
| `../src/Model/Base/BaseTable2Query.php`               | yes |  Table2 query base class to retrieve the data from database |
| `../src/Model/Base/BaseTable2Peer.php`                | yes |  Table2 base object to store field names, table name, constants related to model |
| `../src/Model/Table1.php`                             | no |  Table1 active record class who can be expand for your needs |
| `../src/Model/Table1Query.php`                        | no |  Table1 query class who can be expand for your needs |   
| `../src/Model/Table2.php`                             | no |  Table2 active record class who can be expand for your needs |
| `../src/Model/Table2Query.php`                        | no |  Table2 query class who can be expand for your needs |
# Light Query Builder @ElePHPant

[![Maintainer](http://img.shields.io/badge/maintainer-@sergiodanilojr-blue.svg?style=flat-square)](https://twitter.com/sergiodanilojr)
[![Source Code](http://img.shields.io/badge/source-elephpant/light-query-builder-blue.svg?style=flat-square)](https://github.com/sergiodanilojr/light-query-builder)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/elephpant/light-query-builder.svg?style=flat-square)](https://packagist.org/packages/elephpant/light-query-builder)
[![Latest Version](https://img.shields.io/github/release/elephpant/light-query-builder.svg?style=flat-square)](https://github.com/sergiodanilojr/light-query-builder/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/sergiodanilojr/light-query-builder.svg?style=flat-square)](https://scrutinizer-ci.com/g/sergiodanilojr/light-query-builder)
[![Quality Score](https://img.shields.io/scrutinizer/g/sergiodanilojr/light-query-builder.svg?style=flat-square)](https://scrutinizer-ci.com/g/sergiodanilojr/light-query-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/elephpant/light-query-builder.svg?style=flat-square)](https://packagist.org/packages/elephpant/light-query-builder)

###### 
Firstly Light Query Builder's Awesome! And with it you can construct whatever you want in SQL. 

Primeiramente Light Query Builder é Maravilhoso. E com ele você pode construir qualquer querye SQL que quiser.

###### NOTE: However Light Query Builderjust work currently with MySQL Driver, because a BETA Version. Soon it'll work with others drivers.

### Highlights

- Extremaly Easy
- reading, writing, updating and removing data from the Database
- Construct all your queries with this components
- Improve the funcionalities of this component extendin it
- It work with enviroment variables to set all settings of the database
- Composer ready and PSR-2 compliant (Pronto para o Composer e compatível com PSR-2)

###### BEFORE INSTALL!

For you work with this component, is important work with a component like ```vlucas/dotenv``` for you set your enviroment variables;


````dotenv
DB_DRIVER="mysql"
DB_PORT="3306"
DB_HOST="your_database_host"
DB_USER="root"
DB_PASSWORD="passworddb"
DB_NAME="elephpant"
````


## Installation

Ligh Query Builder is available via Composer:

```bash
"elephpant/light-query-builder": "*"
```

or run

```bash
composer require elephpant/light-query-builder
```

## Documentation

### Quick Start
```php
<?php

require __DIR__ . "/vendor/autoload.php";

use ElePHPant\LightQueryBuilder;

$lightQB = (new LightQueryBuilder())::setTable("users")->setFetchClass(stdClass::class);
```


### Select
```php
$select = $lightQB->select();
//Returns 'SELECT * FROM users'

$selectWithColumns = $lightQB->select("fullname, email");
//Returns 'SELECT fullname, email FROM users';
```


### Where
```php
$where = $select->where("gender = :g", "g=male");
//Returns 'SELECT * FROM users WHERE gender = :g' -> working with bind param in PDO
```

### Operators AND OR BETWEEN
```php
$where->and("id >=2")->or("id <= 10");
//Returns 'SELECT * FROM users WHERE gender = :g AND id >= 2 OR id <= 10'

$between = $select->where("DATE(birth)")->between("'2020-03-17'", "'2020-04-01'");
//Returns 'SELECT * FROM users WHERE DATE(birth) BETWEEN '2020-03-17' AND '2020-04-01''
```


### JOINs
```php
$lightQB->join("fullname", "clients", "client.user=users.id", LightQueryBuilder::INNER_JOIN);
//Returns 'SELECT fullname FROM users INNER JOIN clients ON client.user=users.id'

$lightQB->join("fullname", "clients", "client.user=users.id", LightQueryBuilder::RIGHT_JOIN);
//Returns 'SELECT fullname FROM users RIGHT JOIN clients ON client.user=users.id'

//[...]
```


###Limit and Offset
```php
$select->limit(3)->offset(2);
//Returns 'SELECT * FROM users LIMIT 3 OFFSET 2'
```

###Count */
```php
$select->count();
//Returns all RowCounts of the consult
```


###Match Against
```php
$lightQB->match("fullname, email", "Pedro", true);
//Returns the result of alll users that match with the fullname or email with 'Pedro'.
```

###Write  your own Query
```php
$lightQB->toQuery("
    SELECT * FROM my_table 
    WHERE id = 2
")->limit(2)->offset(1);
```

##CRUD

###Create
```php
$create = $lightQB->create(array(...));
```

###Featching Data (Read)
```php
$select->get(); //Like that it'll bring only one result (first) [object]
$select->get(true); //Like that it'll bring all results [array]
```

###Update
```php
$update = $lightQB->update(array(...), "WHERE id = :id", "id=2");
```

###Delete
```php
$lightQB->delete("WHERE id = :id", "id=2");
```

##Debugging
```php
var_dump($lightQB->getFail(), $lightQB->getQuery());
```

### LightQueryBuilder's Extensible

````php
use ElePHPant\LightQueryBuilder;

/**
 * Class MyQueryBuilder
 */
class MyQueryBuilder extends LightQueryBuilder
{
    /**
     * @param string $column
     * @param string|null $condition
     * @return MyQueryBuilder
     */
    public function avg(string $column, ?string $condition): self
    {
        $select = $this->select("AVG({$column})");

        if ($condition) {
            return $select->where($condition);
        }

        return $select;
    }

    /**
     * @param string $columns
     * @param string $condition
     * @return MyQueryBuilder
     */
    public function sum(string $columns, string $condition): self
    {
        $select = $this->select("SUM({$columns})");

        if ($condition) {
            return $select->where($condition);
        }

        return $select;
    }

}
````

## Contributing

Please see [CONTRIBUTING](https://github.com/sergiodanilojr/light-query-builder/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email sergiodanilojr@hotmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para sergiodanilojr@hotmail.com em vez de usar o rastreador de problemas.

Thank you

## Credits

- [Sérgio Danilo Jr.](https://github.com/sergiodanilojr) (Developer)
- [All Contributors](https://github.com/sergiodanilojr/light-query-builder/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/sergiodanilojr/light-query-builder/blob/master/LICENSE) for more inflight-query-builderation.
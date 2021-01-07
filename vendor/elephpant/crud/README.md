# CRUD @ElePHPant

[![Maintainer](http://img.shields.io/badge/maintainer-@sergiodanilojr-blue.svg?style=flat-square)](https://twitter.com/sergiodanilojr)
[![Source Code](http://img.shields.io/badge/source-elephpant/crud-blue.svg?style=flat-square)](https://github.com/sergiodanilojr/crud)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/elephpant/crud.svg?style=flat-square)](https://packagist.org/packages/elephpant/crud)
[![Latest Version](https://img.shields.io/github/release/elephpant/crud.svg?style=flat-square)](https://github.com/sergiodanilojr/crud/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/sergiodanilojr/crud.svg?style=flat-square)](https://scrutinizer-ci.com/g/sergiodanilojr/crud)
[![Quality Score](https://img.shields.io/scrutinizer/g/sergiodanilojr/crud.svg?style=flat-square)](https://scrutinizer-ci.com/g/sergiodanilojr/crud)
[![Total Downloads](https://img.shields.io/packagist/dt/elephpant/crud.svg?style=flat-square)](https://packagist.org/packages/elephpant/crud)

###### 
CRUD is a abstraction of PDO to simplify reading, writing, updating and removing data from the Database.

CRUD é uma abstração para simplificar a leitura, escrita, atualização e remoção de dados do Banco de Dados.

###### NOTE: However CRUD just work currently with MySQL Driver, because a BETA Version. Soon it'll work with others drivers.

### Highlights

- Extremaly Easy
- Executa Leitura, Escrita e remoção de dados no Banco de dados
- Trabalha com variáveis de Ambiente para setar as configurações do Banco de dados
- Composer ready and PSR-2 compliant (Pronto para o Composer e compatível com PSR-2)

###### BEFORE INSTALL!

For you work with this component, is important work with a component like ````bash vlucas/dotenv```` for you set your enviroment variables;

````dotenv
DB_HOST="your_database_host"
DB_USER="root"
DB_PASSWORD="passworddb"
DB_NAME="elephpant"
````


## Installation

CRUD is available via Composer:

```bash
"elephpant/crud": "*"
```

or run

```bash
composer require elephpant/crud
```

#### Documentation

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use ElePHPant\CRUD;

/* QuickStart with CRUD class :: Call the class and set table from database that you'll use */
$crud = (new CRUD())::setTable("users");

/* Create  */
$arrayWithData = array(...);
$create = $crud->create($arrayWithData);

/* Reading Data :: For Default the read Method utilize the \stdClass like FETCH_CLASS, but ou can utilize other Concrete class for thar */
$read = $crud->read(stdClass::class, true);

/* Update */
$update = $crud->update($arrayWithData, "gender = 'male'");

/* Delete */
$delete = $crud->delete("id = :id", "id=1");

/* Setting Params with CRUD Class */
$params = "gender = :gender";
$crud->setParams($params);

/* DEBUGGING :: For you identify your Query*/
var_dump($crud->getQuery());

/* PDOException */
if (!$crud->create($arrayWithData)) {
    var_dump($crud->getFail());
}
?>

```


## Contributing

Please see [CONTRIBUTING](https://github.com/sergiodanilojr/crud/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email sergiodanilojr@hotmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para sergiodanilojr@hotmail.com em vez de usar o rastreador de problemas.

Thank you

## Credits

- [Sérgio Danilo Jr.](https://github.com/sergiodanilojr) (Developer)
- [All Contributors](https://github.com/sergiodanilojr/crud/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/sergiodanilojr/crud/blob/master/LICENSE) for more infcrudation.
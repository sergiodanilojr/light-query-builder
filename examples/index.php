<?php

require __DIR__ . "/../vendor/autoload.php";

use ElePHPant\LightQueryBuilder;

$lightQB = (new LightQueryBuilder())::setTable("users")
    ->setFetchClass(stdClass::class);

/* Select */
$select = $lightQB->select();
//Returns 'SELECT * FROM users'

$selectWithColumns = $lightQB->select("fullname, email");
//Returns 'SELECT fullname, email FROM users';

/* Where */
$where = $select->where("gender = :g", "g=male");
//Returns 'SELECT * FROM users WHERE gender = :g' -> working with bind param in PDO

/* AND OR BETWEEN */
$where->and("id >=2")->or("id <= 10");
//Returns 'SELECT * FROM users WHERE gender = :g AND id >= 2 OR id <= 10'

$between = $select->where("DATE(birth)")->between("'2020-03-17'", "'2020-04-01'");
//Returns 'SELECT * FROM users WHERE DATE(birth) BETWEEN '2020-03-17' AND '2020-04-01''

/* JOINs */
$lightQB->join("fullname", "clients", "client.user=users.id", LightQueryBuilder::INNER_JOIN);
//Returns 'SELECT fullname FROM users INNER JOIN clients ON client.user=users.id'

$lightQB->join("fullname", "clients", "client.user=users.id", LightQueryBuilder::RIGHT_JOIN);
//Returns 'SELECT fullname FROM users RIGHT JOIN clients ON client.user=users.id'

//[...]

/* Limit and Offset */
$select->limit(3)->offset(2);
//Returns 'SELECT * FROM users LIMIT 3 OFFSET 2'

/* Count */
$select->count();
//Returns all RowCounts of the consult

/* Match Against */
$lightQB->match("fullname, email", "Pedro", true);
//Returns the result of alll users that match with the fullname or email with 'Pedro'.

/* Write  your own Query*/
$lightQB->toQuery("
    SELECT * FROM my_table 
    WHERE id = 2
")->limit(2)->offset(1);


/* CRUD */

//Create
$create = $lightQB->create(array(...));

//Featching Data
$select->get(); //Like that it'll bring only one result (first) [object]
$select->get(true); //Like that it'll bring all results [array]

//Update
$update = $lightQB->update(array(...), "WHERE id = :id", "id=2");

//Delete
$lightQB->delete("WHERE id = :id", "id=2");

/* Debugging */
var_dump($lightQB->getFail(), $lightQB->getQuery());





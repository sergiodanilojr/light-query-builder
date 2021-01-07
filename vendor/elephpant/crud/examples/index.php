<?php

require __DIR__ . "/../vendor/autoload.php";

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

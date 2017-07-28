<?php
/**
 * Created by PhpStorm.
 * User: binll
 * Date: 28/07/2017
 * Time: 11:38
 */
require_once 'vendor/autoload.php';

$app = new \Slim\App;

$app->get("/pruebas",function() use($app){
    echo "hola mundo desde slim php";
});
$app->run();
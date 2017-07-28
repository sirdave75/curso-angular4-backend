<?php
/**
 * Created by PhpStorm.
 * User: binll
 * Date: 28/07/2017
 * Time: 11:38
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once 'vendor/autoload.php';

$app = new \Slim\App;




$db = new mysqli('localhost','dave', 'new_york', 'curso_angular4');

$app->get("/pruebas",function() use($app,$db){
    echo "hola mundo desde slim php";

});

//listar todos los productos
$app->get('/productos', function(Request $request, Response $response) use($db){

    $sql = "select * from productos order by id desc";
    $query = $db->query($sql);

    $productos = [];
    while($producto = $query->fetch_assoc()){
        $productos[] = $producto;
    }

    $result = array(
        "status" => 'success',
        "code" => 200,
        "data" => $productos
    );

    echo json_encode($result);
});

//devolver un solo producto

$app->get('/producto/{id}', function(Request $request, Response $response, $args ) use($db){

    $sql = 'select * from productos where id = '.$args["id"];
    $query = $db->query($sql);
    $result = array(
                "status" => "error",
                "code" => 404,
                "message" => "Producto no disponible"
                );
    if($query -> num_rows == 1){
        $producto = $query->fetch_assoc();
        $result = array(
            "status" => "success",
            "code" => 200,
            "data" => $producto
        );
    }


    echo json_encode($result);
});

//eliminar un producto

$app->get('/delete-producto/{id}', function(Request $request, Response $response, $args ) use($db){
   $sql = "DELETE from productos where id = ".$args["id"];

   $query = $db->query($sql);

    $result = array(
        "status" => "error",
        "code" => 404,
        "message" => "Producto no eliminado"
    );

    if($query){

        $result = array(
            "status" => "success",
            "code" => 200,
            "message" => "Producto eliminado correctamente"
        );
    }
    echo json_encode($result);

});

//actualizar un producto

//subir una imagen a un producto

//guardar productos
$app->post('/productos',function(Request $request, Response $response) use($app,$db){
     $json = $request->getParsedBody();

     $data = json_decode($json["json"],true);

    var_dump($json);

     if(!isset($data['nombre'])) $data['nombre'] = null;
     if(!isset($data['description'])) $data['description'] = null;
     if(!isset($data['precio'])) $data['precio'] = null;
     if(!isset($data['imagen'])) $data['imagen'] = null;

     $query = "insert into productos values(NULL,".
             "'{$data['nombre']}',".
             "'{$data['description']}',".
             "'{$data['precio']}',".
             "'{$data['imagen']}'".
             ")";
     var_dump($query);

     $insert = $db ->query($query);
    $result = array(
        "status" => 'error',
        "code" => 404,
        "message" => 'El producto no se ha creado correctamente'
    );
     if($insert) {
         $result = array(
             "status" => 'success',
             "code" => 200,
             "message" => 'Producto creado correctamente'
         );
     }
     echo json_encode($result);

});

$app->run();
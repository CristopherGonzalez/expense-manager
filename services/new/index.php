<?php
include "../../core/autoload.php";
include "../../core/modules/index/model/CompanyData.php";
include "../../core/modules/index/model/EntityData.php";
include "../../core/modules/index/model/TypeData.php";
//var_dump($_SERVER);
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if(isset($_GET['id_company']) && !empty($_GET['id_company'])){
        $id_company = $_GET['id_company'];
        $entities = EntityData::getAll($id_company);
        header("HTTP/1.1 200 OK");
        $response = array();
        foreach($entities as $index=>$entity){
            $type = EntityData::getType($entity->tipo);
            $response['entity_'.($index+1)] = [
                    "id_entity"=> $entity->id,
                    "name"=> $entity->name,
                    "type"=> $type->tipo,
                    "id_type"=> $entity->tipo,
                    "id_category"=> $entity->category_id
                ];
        }

        echo json_encode($response);
    }else{
        header("HTTP/1.1 200 OK");
        echo 'id_company no viene en la peticion.';
    }
    exit();
    header("HTTP/1.1 200 OK");
    echo "Request get";
    exit();
}
// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['id_company']) && !empty($_POST['id_company'])){
        $id_company = $_POST['id_company'];
        $entities = EntityData::getAll($id_company);
        header("HTTP/1.1 200 OK");
        $response = array();
        foreach($entities as $index=>$entity){
            $type = EntityData::getType($entity->tipo);
            $response['entity_'.($index+1)] = [
                    "id_entity"=> $entity->id,
                    "name"=> $entity->name,
                    "type"=> $type->tipo,
                    "id_type"=> $entity->tipo,
                    "id_category"=> $entity->category_id
                ];
        }

        echo json_encode($response);
    }else{
        header("HTTP/1.1 200 OK");
        echo 'id_company no viene en la peticion.';
    }
    exit();
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    header("HTTP/1.1 200 OK");
    echo "Request delete";
    var_dump($_DELETE);
    exit();
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{ 
    header("HTTP/1.1 200 OK");
    echo "Request put";
    var_dump($_PUT);
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

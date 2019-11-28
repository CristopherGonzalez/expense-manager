<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    header("HTTP/1.1 200 OK");
      echo "Request get";
      var_dump($_SERVER);
      exit();
}
// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    header("HTTP/1.1 200 OK");
    echo "Request post";
    var_dump($_POST);


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

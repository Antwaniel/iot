<?php
require_once "connection.php";

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case "GET":
        //CONSULTA
        $c =connection();
        $c->exec("use iot");
        if(isset($_GET['id'])){ 
            $id = $_GET['id'];
            $s = $c->prepare("SELECT * FROM sensors WHERE id=:pid");
            $s->bindValue(":pid", $id);
            $s->execute();
            $s->setFetchMode(PDO::FETCH_ASSOC);
            $r= $s->fetch();
        }else{
            $s = $c->prepare("SELECT * FROM sensors");
            $s->execute();
            $s->setFetchMode(PDO::FETCH_ASSOC);
            $r= $s->fetchALL();
        }
        echo json_encode($r);
        
        break;
    case "POS":
        //Insertar
        break;
    case "PUT":
        //Actualizar
        break;
    case "DELETE";
        //Eliminar
        break;
}

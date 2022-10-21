<?php
require_once "connection.php";
require_once "jwt.php";
if($_SERVER["REQUEST_METHOD"]=="OPTIONS"){
    exit(0);
}
$jwt =apache_request_headers()["Authorization"];
if(strstr($jwt, "Bearer")){
    $jwt=substr($jwt,7);
}
if(JWT::verify($jwt, "12345678")){
    header(("HTTP/1.1 401 Unauthorized"));
    exit();
}


// POSTMAN:
// GET = user&pas: params
//POST=  body:form data

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case "GET":
        //CONSULTA
        $connection =connection();
        $connection->exec("use iot");
        if(isset($_GET['id'])){ 
            $id = $_GET['id'];
            $s = $connection->prepare("SELECT * FROM register WHERE id=:pid");
            $s->bindValue(":pid", $id);
            $s->execute();
            $s->setFetchMode(PDO::FETCH_ASSOC);
            $result= $s->fetch();
        }else{
            $statement = $connection->prepare("SELECT * FROM register");
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result= $statement->fetchALL();
        }
        echo json_encode($result);
        break;


    case "POST":
        
        if (!isset($_POST['type']) && !isset($_POST['value'])) {
            header("HTTP/1.1 400 Bad Request");
            return;
        }
        $connection = connection();
        $statement = $connection->prepare("INSERT INTO register(user,type,value,date) VALUES(:u, :t, :v, :d)");
        $statement->bindValue(":u", "admin");
        $statement->bindValue(":t", $_POST['type']);
        $statement->bindValue(":v", $_POST['value']);
        $statement->bindValue(":d", date("Y-m-d H:m:s"));
        $statement->execute();
        if ($statement->rowCount()==0) {
            header("HTTP/1.1 400 Bad Request");
            return;
        }
            echo json_encode(["status"=>"success", "id"=>$connection->lastInsertId()]);
        break;

    case "PUT":
        //Actualizar
        if (!isset($_GET['type']) && !isset($_GET['value'])&& !isset($_GET['id'])) {
            header("HTTP/1.1 400 Bad Request");
            return;
        }else{
        $connection = connection();
        $statement = $connection->prepare("UPDATE register SET type=:t, value=:v WHERE id=:id");
        $statement->bindValue("id", $_GET['id']);
        $statement->bindValue(":t", $_GET['type']);
        $statement->bindValue(":v", $_GET['value']);
        $statement->execute();     
            echo json_encode(["status"=>"success"]);
        }
        break;


    case "DELETE";
        //Eliminar
        if (!isset($_GET['id'])) {
            header("HTTP/1.1 400 Bad Request");
            return;
        }else{
        $connection = connection();
        $statement = $connection->prepare("DELETE FROM register WHERE id=:id");
        $statement->bindValue("id", $_GET['id']);
        $statement->execute();     
            echo json_encode(["status"=>"success"]);
        }
        break;
}

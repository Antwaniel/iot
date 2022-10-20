<?php 

require_once "connection.php";
require_once "jwt.php";



if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
        $u= $_REQUEST['user'];
        $p= $_REQUEST['pass'];
        $connection =connection();
        $statement = $connection->prepare("SELECT user,role FROM users WHERE user=:u AND pass=:p");
        $statement->bindValue(":u", $u);
        $statement->bindValue(":p", md5($p));
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result= $statement->fetch();
        if($result){
            $result =[
                "status" => "ok",
                "jwt"=>JWT::create($result, "12345678")
            ];
            echo "hola";
           
        }else{
            $result =["status" => "error"]; 
        }

}else{
    header(("HTTP/1.1 400 Bad Request"));
 
}





?>
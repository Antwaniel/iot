<?php
require_once "connection.php";

$c = connection();

if($c) {
    echo "you're connected to the database UwU";
}
